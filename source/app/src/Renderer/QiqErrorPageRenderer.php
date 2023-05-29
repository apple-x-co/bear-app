<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Renderer;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Qiq\Template;
use Ray\Di\Di\Named;

use function assert;
use function is_array;

class QiqErrorPageRenderer implements RenderInterface
{
    public function __construct(
        #[Named('qiq_template_dir')] private readonly string $templateDir,
        #[Named('qiq_error_view_name')] private readonly string|null $errorViewName = null,
    ) {
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function render(ResourceObject $ro): string
    {
        $tpl = Template::new($this->templateDir);
        $tpl->setView($this->errorViewName);
        assert(is_array($ro->body));
        $tpl->setData($ro->body);
        $ro->view = $tpl();

        return $ro->view;
    }
}
