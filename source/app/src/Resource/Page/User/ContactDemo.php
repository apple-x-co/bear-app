<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use AppCore\Domain\Locale\Locale;
use AppCore\Domain\Uri\PublicUriBuilderInterface;
use BEAR\RepositoryModule\Annotation\NoHttpCache;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\FormStep;
use MyVendor\MyProject\InputQuery\Customer\ContactDemoInput;
use MyVendor\MyProject\Resource\Page\BaseUserPage;
use Ray\Di\Di\Named;
use Ray\InputQuery\Attribute\Input;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

#[NoHttpCache]
class ContactDemo extends BaseUserPage
{
    public function __construct(
        protected readonly PublicUriBuilderInterface $publicUriBuilder,
        protected readonly Locale $requestLocale,
        #[Named('user_contact_demo_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
        $this->body['screen'] = FormStep::Input;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings("PHPMD.StaticAccess")
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    #[FormValidation]
    public function onPost(
        #[Input]
        ContactDemoInput $input,
    ): static {
        $step = FormStep::tryFrom((string) $input->step) ?? FormStep::Input;
        if ($step->isComplete()) {
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [
                ResponseHeader::LOCATION => (string) $this->publicUriBuilder->build(
                    '/user/contact-complete-demo',
                    [],
                    $this->requestLocale,
                ),
            ];

            return $this;
        }

        $this->body['screen'] = $step;

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
