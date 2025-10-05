<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Application\Admin as AdminUseCase;
use AppCore\Application\GetVerificationCodeUseCase;
use AppCore\Application\VerifyVerificationCodeUseCase;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Encrypter\EncryptPass;
use AppCore\Domain\Hasher\PasswordHasher;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Language\LangDir;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\EmailConfig;
use AppCore\Domain\Mail\EmailConfigInterface;
use AppCore\Domain\Mail\EmailDir;
use AppCore\Domain\Mail\TemplateRenderer;
use AppCore\Domain\Mail\TemplateRendererInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\ServiceName;
use AppCore\Domain\Test\TestRepositoryInterface;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Domain\WebSignature\UrlSignatureEncrypterInterface;
use AppCore\Infrastructure\Persistence\AdminPermissionRepository;
use AppCore\Infrastructure\Persistence\AdminRepository;
use AppCore\Infrastructure\Persistence\AdminTokenRepository;
use AppCore\Infrastructure\Persistence\TestRepository;
use AppCore\Infrastructure\Persistence\ThrottleRepository;
use AppCore\Infrastructure\Shared\AdminLogger;
use AppCore\Infrastructure\Shared\Encrypter;
use AppCore\Infrastructure\Shared\QueueMail;
use AppCore\Infrastructure\Shared\SecureRandom;
use AppCore\Infrastructure\Shared\SmtpMail;
use AppCore\Infrastructure\Shared\UserLogger;
use AppCore\Infrastructure\Shared\UrlSignatureEncrypter;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use MyVendor\MyProject\Provider\LanguageProvider;
use MyVendor\MyProject\Provider\PhpMailerProvider;
use PHPMailer\PHPMailer\PHPMailer;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function getenv;
use function parse_str;
use function random_bytes;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class BaseModule extends AbstractModule
{
    public function __construct(
        private readonly string $emailDir,
        private readonly string $langDir,
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->core();
        $this->language();
        $this->logger();
        $this->url();
        $this->email();
        $this->repository();
        $this->usecase();

        $this->http();

        // TODO: AWS SKD の設定などはここで行う。ステージング環境や本番環境で必要な設定は ProdModule で行う。
    }

    private function core(): void
    {
        $this->bind()->annotatedWith(ServiceName::class)->toInstance((string) getenv('SERVICE_NAME'));

        $this->bind()->annotatedWith(EncryptPass::class)->toInstance((string) getenv('ENCRYPT_PASS'));
        $this->bind(EncrypterInterface::class)->to(Encrypter::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('hash_salt')->toInstance(random_bytes(32));
        $this->bind(SecureRandomInterface::class)->to(SecureRandom::class)->in(Scope::SINGLETON);

        $this->bind(PasswordHasherInterface::class)->to(PasswordHasher::class)->in(Scope::SINGLETON);

        $this->bind(UrlSignatureEncrypterInterface::class)->to(UrlSignatureEncrypter::class)->in(Scope::SINGLETON);
    }

    public function language(): void
    {
        $this->bind()->annotatedWith(LangDir::class)->toInstance($this->langDir);
        $this->bind(LanguageInterface::class)->toProvider(LanguageProvider::class)->in(Scope::SINGLETON);
    }

    private function logger(): void
    {
        $this->bind(LoggerInterface::class)->annotatedWith('admin')->to(AdminLogger::class)->in(Scope::SINGLETON);
        $this->bind(LoggerInterface::class)->annotatedWith('user')->to(UserLogger::class)->in(Scope::SINGLETON);
    }

    private function url(): void
    {
        $this->bind()->annotatedWith('admin_base_url')->toInstance(getenv('ADMIN_BASE_URL'));
    }

    private function email(): void
    {
        $this->bind()->annotatedWith('admin_address_email')->toInstance(getenv('ADMIN_EMAIL_ADDRESS'));
        $this->bind()->annotatedWith('admin_address_name')->toInstance('ADMINISTRATOR');
        $this->bind(AddressInterface::class)
             ->annotatedWith('admin')
             ->toConstructor(Address::class, [
                 'email' => 'admin_address_email',
                 'name' => 'admin_address_name',
             ])
             ->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('smtp_hostname')->toInstance(getenv('SMTP_HOST'));
        $this->bind()->annotatedWith('smtp_port')->toInstance((int) getenv('SMTP_PORT'));
        $this->bind()->annotatedWith('smtp_username')->toInstance(getenv('SMTP_USER'));
        $this->bind()->annotatedWith('smtp_password')->toInstance(getenv('SMTP_PASS'));
        $smtpOptions = [];
        parse_str((string) getenv('SMTP_OPTION'), $smtpOptions);
        $this->bind()->annotatedWith('smtp_options')->toInstance($smtpOptions);
        $this->bind(EmailConfigInterface::class)
             ->annotatedWith('default')
             ->toConstructor(EmailConfig::class, [
                 'hostname' => 'smtp_hostname',
                 'port' => 'smtp_port',
                 'username' => 'smtp_username',
                 'password' => 'smtp_password',
                 'options' => 'smtp_options',
             ])
             ->in(Scope::SINGLETON);
        $this->bind(PHPMailer::class)->toProvider(PhpMailerProvider::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith(EmailDir::class)->toInstance($this->emailDir);
        $this->bind(TransportInterface::class)->annotatedWith('SMTP')->to(SmtpMail::class)->in(Scope::SINGLETON);
        $this->bind(TransportInterface::class)->annotatedWith('queue')->to(QueueMail::class)->in(Scope::SINGLETON);

        $this->bind(TemplateRendererInterface::class)->to(TemplateRenderer::class)->in(Scope::SINGLETON);
    }

    private function repository(): void
    {
        $this->bind(AdminPermissionRepositoryInterface::class)->to(AdminPermissionRepository::class)->in(Scope::SINGLETON);
        $this->bind(AdminRepositoryInterface::class)->to(AdminRepository::class)->in(Scope::SINGLETON);
        $this->bind(AdminTokenRepositoryInterface::class)->to(AdminTokenRepository::class)->in(Scope::SINGLETON);
        $this->bind(TestRepositoryInterface::class)->to(TestRepository::class)->in(Scope::SINGLETON);
        $this->bind(ThrottleRepositoryInterface::class)->to(ThrottleRepository::class)->in(Scope::SINGLETON);
    }

    private function usecase(): void
    {
        $this->bind(AdminUseCase\CreateAdminEmailUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\CreateAdminUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\DeleteAdminEmailUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\DeleteAdminUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\ForgotAdminPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\GetAdminUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\GetForgotAdminPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\GetJoinedAdminUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\JoinAdminUserCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\ResetAdminPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\UpdateAdminPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(AdminUseCase\VerifyAdminEmailUseCase::class)->in(Scope::SINGLETON);

        $this->bind(GetVerificationCodeUseCase::class)->in(Scope::SINGLETON);
        $this->bind(VerifyVerificationCodeUseCase::class)->in(Scope::SINGLETON);
    }

    private function http(): void
    {
        $this->bind(HttpClientInterface::class)->to(HttpClient::class);
    }
}
