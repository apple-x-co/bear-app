<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use AppCore\Domain\Document\DocumentReaderInterface;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\Locale\Locale;
use AppCore\Domain\Uri\AdminStaticUriBuilderInterface;
use AppCore\Domain\Uri\AdminUriBuilderInterface;
use AppCore\Domain\Uri\PublicStaticUriBuilderInterface;
use AppCore\Domain\Uri\PublicUriBuilderInterface;
use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedFieldset;
use MyVendor\MyProject\Form\ExtendedForm;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Qiq\Helper\Html\HtmlHelpers;

use function array_merge;
use function implode;
use function is_string;
use function sprintf;

use const PHP_EOL;

/**
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 * @SuppressWarnings("PHPMD.TooManyMethods")
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class QiqCustomHelpers extends HtmlHelpers
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        private readonly AdminStaticUriBuilderInterface $adminStaticUriBuilder,
        private readonly AdminUriBuilderInterface $adminUriBuilder,
        private readonly DocumentReaderInterface $documentReader,
        private readonly LanguageInterface $language,
        private readonly PublicStaticUriBuilderInterface $publicStaticUriBuilder,
        private readonly PublicUriBuilderInterface $publicUriBuilder,
        private readonly Locale $requestLocale,
        private readonly ServerRequestInterface $serverRequest,
    ) {
        parent::__construct(null);
    }

    /**
     * @SuppressWarnings("PHPMD.LongVariable")
     * @see https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/
     */
    public function cfTurnstileWidget(
        string $cloudflareTurnstileSiteKey,
        string $size = 'normal',
        string $action = 'none',
        string|null $checked = null,
        string|null $expired = null,
        string|null $error = null,
        string|null $timeout = null,
    ): string {
        if ($cloudflareTurnstileSiteKey === '') {
            return '';
        }

        $attribs = [
            'class' => 'cf-turnstile',
            'data-action' => $action,
            'data-language' => 'ja',
            'data-sitekey' => $cloudflareTurnstileSiteKey,
            'data-size' => $size,
            'data-theme' => 'light',
        ];

        if (is_string($checked)) {
            $attribs['data-callback'] = $checked;
        }

        if (is_string($expired)) {
            $attribs['data-expired-callback'] = $expired;
        }

        if (is_string($error)) {
            $attribs['data-error-callback'] = $error;
        }

        if (is_string($timeout)) {
            $attribs['data-timeout-callback'] = $timeout;
        }

        return sprintf('<div %s></div>', $this->a($attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminCheckBox(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminFieldsetError(
        ExtendedFieldset $fieldset,
        string $input,
        string $tag = 'span',
        array $attribs = [],
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

    /** @param array<string, string> $attribs */
    public function adminFile(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full p-3 bg-white border border-[#6b7280] placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
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

    /** @param array<string, string> $attribs */
    public function adminHidden(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);
        $spec['type'] = 'hidden';

        $defaultAttribs = [];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminRadio(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminSelect(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminText(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminTextArea(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function adminSubmit(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        if (isset($attribs['value'])) {
            $spec['value'] = $attribs['value'];
            unset($attribs['value']);
        }

        $defaultAttribs = [
            'value' => 'Submit',
            'class' => 'py-2 px-4 bg-indigo-500 text-white text-sm font-sans font-bold tracking-wider rounded-full shadow-lg shadow-indigo-500/50 focus:outline-none hover:bg-indigo-600 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none',
        ];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerCheckBox(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerFieldsetError(
        ExtendedFieldset $fieldset,
        string $input,
        string $tag = 'span',
        array $attribs = [],
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

    /** @param array<string, string> $attribs */
    public function managerFile(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full p-3 bg-white border border-[#6b7280] placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerFormError(
        ExtendedForm $form,
        string $input,
        string $tag = 'span',
        array $attribs = [],
    ): string {
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

    /** @param array<string, string> $attribs */
    public function managerHidden(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);
        $spec['type'] = 'hidden';

        $defaultAttribs = [];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerRadio(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerSelect(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerText(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerTextArea(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin transition duration-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function managerSubmit(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        if (isset($attribs['value'])) {
            $spec['value'] = $attribs['value'];
            unset($attribs['value']);
        }

        $defaultAttribs = [
            'value' => 'Submit',
            'class' => 'py-2 px-4 bg-indigo-500 text-white text-sm font-sans font-bold tracking-wider rounded-full shadow-lg shadow-indigo-500/50 focus:outline-none hover:bg-indigo-600 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none',
        ];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    public function requestLocale(): Locale
    {
        return $this->requestLocale;
    }

    public function csrfTokenField(ExtendedForm $form): AbstractInput
    {
        return $form->widget($form->get('__csrf_token'));
    }

    /** @param array<string, mixed> $params */
    public function adminStaticUri(string $path, array $params = []): UriInterface
    {
        return $this->adminStaticUriBuilder->build($path, $params);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function adminUri(string $path, array $params = []): UriInterface
    {
        return $this->adminUriBuilder->build(
            $path,
            $params,
        );
    }

    /** @param array<string, mixed> $params */
    public function publicStaticUri(string $path, array $params = []): UriInterface
    {
        return $this->publicStaticUriBuilder->build($path, $params);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function publicUri(string $path, array $params = [], string|null $locale = null): UriInterface
    {
        return $this->publicUriBuilder->build(
            $path,
            $params,
            $locale === null ? $this->requestLocale : Locale::from($locale),
        );
    }

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    public function hreflangLinks(): string
    {
        $uri = $this->serverRequest->getUri();

        $origin = $uri->getScheme() . '://' . $uri->getHost();
        $search = $uri->getQuery() === '' ? '' : '?' . $uri->getQuery();

        $uriPath = Locale::stripPrefixFromPath($uri->getPath());

        $tagList = [];
        $supportedLocaleList = Locale::cases();
        foreach ($supportedLocaleList as $supportedLocale) {
            $link = $origin . '/' . $supportedLocale->value . $uriPath . $search;
            $tagList[] = sprintf(
                '<link rel="alternate" hreflang="%s" href="%s">',
                $supportedLocale->value,
                $link,
            );
        }

        return implode(PHP_EOL, $tagList);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @SuppressWarnings("PHPMD.ShortMethodName")
     */
    public function t(string $key, array $params = []): string
    {
        return $this->language->get($key, $params);
    }

    public function doc(string $name): string
    {
        return $this->documentReader->read($name, $this->requestLocale);
    }
}
