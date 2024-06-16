<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Domain\Language\LanguageInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Resource\Page\AdminPage;

class Error extends AdminPage
{
    public function __construct(
        private readonly LanguageInterface $language,
    ) {
    }

    public function onGet(): static
    {
        $message = $this->context->getSessionValue('error:message');
        $returnName = $this->context->getSessionValue('error:returnName');
        $returnUrl = $this->context->getSessionValue('error:returnUrl');

        $this->context->resetSessionValue('error:message');
        $this->context->resetSessionValue('error:returnName');
        $this->context->resetSessionValue('error:returnUrl');

        if ($message === null) {
            $this->renderer = new NullRenderer();
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/index'];

            return $this;
        }

        $this->body['message'] = $this->language->get($message);
        $this->body['returnName'] = $returnName;
        $this->body['returnUrl'] = $returnUrl;

        return $this;
    }
}
