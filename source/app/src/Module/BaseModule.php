<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\Encrypter;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\EmailConfig;
use AppCore\Domain\Mail\EmailConfigInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\PasswordHasher;
use AppCore\Domain\PasswordHasherInterface;
use AppCore\Domain\SecureRandom;
use AppCore\Domain\SecureRandomInterface;
use AppCore\Domain\Test\TestRepositoryInterface;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Persistence\AdminRepository;
use AppCore\Infrastructure\Persistence\AdminTokenRepository;
use AppCore\Infrastructure\Persistence\TestRepository;
use AppCore\Infrastructure\Persistence\ThrottleRepository;
use AppCore\Infrastructure\Shared\AdminLogger;
use AppCore\Infrastructure\Shared\SmtpMail;
use AppCore\Infrastructure\Shared\UserLogger;
use MyVendor\MyProject\Lang\LanguageInterface;
use MyVendor\MyProject\Provider\LanguageProvider;
use MyVendor\MyProject\Provider\PhpMailerProvider;
use PHPMailer\PHPMailer\PHPMailer;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function getenv;
use function parse_str;
use function random_bytes;
use function rtrim;

use const DIRECTORY_SEPARATOR;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class BaseModule extends AbstractModule
{
    public function __construct(
        private string $emailDir,
        private string $langDir,
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->core();
        $this->language();
        $this->logger();
        $this->mailer();
        $this->repository();

        // TODO: AWS SKD の設定などはここで行う。ステージング環境や本番環境で必要な設定は ProdModule で行う。
    }

    private function core(): void
    {
        $this->bind()->annotatedWith('encrypt_pass')->toInstance((string) getenv('ENCRYPT_PASS'));
        $this->bind(EncrypterInterface::class)->to(Encrypter::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('hash_salt')->toInstance(random_bytes(32));
        $this->bind(SecureRandomInterface::class)->to(SecureRandom::class)->in(Scope::SINGLETON);

        $this->bind(PasswordHasherInterface::class)->to(PasswordHasher::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('address_admin_email')->toInstance(getenv('ADMIN_EMAIL_ADDRESS'));
        $this->bind()->annotatedWith('address_admin_name')->toInstance('ADMINISTRATOR');
        $this->bind(AddressInterface::class)
             ->annotatedWith('admin')
             ->toConstructor(Address::class, [
                 'email' => 'address_admin_email',
                 'name' => 'address_admin_name',
             ])
             ->in(Scope::SINGLETON);
    }

    public function language(): void
    {
        $this->bind()->annotatedWith('lang_dir')->toInstance($this->langDir);
        $this->bind(LanguageInterface::class)->toProvider(LanguageProvider::class);
    }

    private function logger(): void
    {
        $this->bind(LoggerInterface::class)->annotatedWith('admin')->to(AdminLogger::class);
        $this->bind(LoggerInterface::class)->annotatedWith('user')->to(UserLogger::class);
    }

    private function mailer(): void
    {
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
        $this->bind(PHPMailer::class)->toProvider(PhpMailerProvider::class);
        $this->bind()->annotatedWith('email_html_dir')
             ->toInstance(rtrim($this->emailDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'html');
        $this->bind()->annotatedWith('email_subject_dir')
             ->toInstance(rtrim($this->emailDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'subject');
        $this->bind()->annotatedWith('email_text_dir')
             ->toInstance(rtrim($this->emailDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'text');
        $this->bind(TransportInterface::class)->annotatedWith('SMTP')->to(SmtpMail::class)->in(Scope::SINGLETON);
    }

    private function repository(): void
    {
        $this->bind(AdminRepositoryInterface::class)->to(AdminRepository::class)->in(Scope::SINGLETON);
        $this->bind(AdminTokenRepositoryInterface::class)->to(AdminTokenRepository::class)->in(Scope::SINGLETON);
        $this->bind(TestRepositoryInterface::class)->to(TestRepository::class)->in(Scope::SINGLETON);
        $this->bind(ThrottleRepositoryInterface::class)->to(ThrottleRepository::class)->in(Scope::SINGLETON);
    }
}
