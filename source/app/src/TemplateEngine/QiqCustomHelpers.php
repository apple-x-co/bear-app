<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use Aura\Html\Helper\Input\AbstractInput;
use BEAR\Sunday\Extension\Router\RouterInterface;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\AdminIdentity;
use MyVendor\MyProject\Form\ExtendedFieldset;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Session\SessionInterface;
use Qiq\Helper\Html\HtmlHelpers;
use Ray\Di\Di\Named;

use function array_merge;
use function is_string;
use function sprintf;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class QiqCustomHelpers extends HtmlHelpers
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        #[Named('google_recaptcha_site_key')] private readonly string $googleRecaptchaSiteKey,
        private readonly RouterInterface $router,
        private readonly SessionInterface $session,
    ) {
        parent::__construct(null);
    }

    public function admin(): AdminIdentity
    {
        return $this->adminAuthenticator->getIdentity();
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminCheckBox(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminFieldsetError(
        ExtendedFieldset $fieldset,
        string $input,
        string $tag = 'span',
        array $attribs = []
    ): string {
        $errorMessages = $fieldset->error($input);
        if (empty($errorMessages)) {
            return '';
        }

        $defaultAttribs = ['class' => 'block text-sm text-rose-500 italic'];

        return sprintf(
            '<%s %s>%s</%s>',
            $tag,
            $this->a(array_merge($defaultAttribs, $attribs)),
            $this->h($errorMessages[0]),
            $tag,
        );
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminFile(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full p-3 bg-white border border-[#6b7280] placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminFormError(ExtendedForm $form, string $input, string $tag = 'span', array $attribs = []): string
    {
        $message = $form->error($input);
        if ($message === '') {
            return '';
        }

        $defaultAttribs = ['class' => 'block text-sm text-rose-500 italic'];

        return sprintf(
            '<%s %s>%s</%s>',
            $tag,
            $this->a(array_merge($defaultAttribs, $attribs)),
            $this->h($message),
            $tag,
        );
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminHidden(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);
        $spec['type'] = 'hidden';

        $defaultAttribs = [];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminRadio(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminSelect(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminText(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminTextArea(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /**
     * @param array<string, string> $attribs
     */
    public function adminSubmit(
        ExtendedForm $form,
        string $input,
        ?ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        if (isset($attribs['value'])) {
            $spec['value'] = $attribs['value'];
            unset($attribs['value']);
        }

        $defaultAttribs = [
            'value' => 'Submit',
            'class' => 'py-2 px-3 bg-sky-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-sky-500/50 focus:outline-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none',
        ];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    public function flashMessage(): ?string
    {
        return $this->session->getFlashMessage();
    }

    public function googleRecaptchaSiteKey(): string
    {
        return $this->googleRecaptchaSiteKey;
    }

    public function csrfTokenField(ExtendedForm $form): AbstractInput
    {
        return $form->widget($form->get('__csrf_token'));
    }

    /**
     * @param array<string, mixed> $params
     */
    public function url(string $routePath, array $params = []): string
    {
        $path = $this->router->generate($routePath, $params);
        if (is_string($path)) {
            return $path;
        }

        return $routePath;
    }
}
