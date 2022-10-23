<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure\Events;

use App\Entity\User;
use App\Infrastructure\Mercure\Service\MercureCookieGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Security\Core\Security;

/**
 * Add the cookie necessary to the responses.
 */
class MercureCookieMiddleware implements EventSubscriberInterface
{
    private Security $security;
    private MercureCookieGenerator $generator;
    private Discovery $discovery;

    public function __construct(MercureCookieGenerator $generator, Security $security, Discovery $discovery)
    {
        $this->security = $security;
        $this->generator = $generator;
        $this->discovery = $discovery;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['setMercureCookie'],
        ];
    }

    public function setMercureCookie(ResponseEvent $event): void
    {
        /* $response = $event->getResponse();
        $request = $event->getRequest();
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()
            || !in_array('text/html', $request->getAcceptableContentTypes())
            || !($user = $this->security->getUser()) instanceof User
        ) {
            return;
        }

        $response->headers->setCookie($this->generator->generate($user)); */
    }
}
