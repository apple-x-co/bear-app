<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Lang\LanguageInterface;
use MyVendor\MyProject\Resource\Page\AdminPage;

class Error extends AdminPage
{
    public function __construct(
        private readonly LanguageInterface $language,
    ) {
    }

    public function onGet(): static
    {
        $message = $this->session->get('error:message');
        $returnName = $this->session->get('error:returnName');
        $returnUrl = $this->session->get('error:returnUrl');

        $this->session->reset('error:message');
        $this->session->reset('error:returnName');
        $this->session->reset('error:returnUrl');

        if ($message === null) {
            $this->renderer = null;
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
