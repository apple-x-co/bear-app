<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Infrastructure\Shared\Session;
use Aura\Session\Session as AuraSession;
use Ray\Di\ProviderInterface;

/** @template-implements ProviderInterface<Session> */
readonly class SessionProvider implements ProviderInterface
{
    private const string SEGMENT_NAME = 'Bebo\Common';

    public function __construct(
        private AuraSession $session,
    ) {
    }

    /** {@inheritDoc} */
    public function get()
    {
        return new Session($this->session->getSegment(self::SEGMENT_NAME));
    }
}
