<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

class AccessDeniedListener implements EventSubscriberInterface
{
    public function __construct(
        public readonly Environment $twig,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP ExceptionListener,
            // to make sure it's called before the default exception listener
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof AccessDeniedException) {
            return;
        }

        $exceptionView = $exception->getCode() ? 'error'.$exception->getCode().'.html.twig' : 'error.html.twig';

        $content = $this->twig->render(
            "bundles/TwigBundle/Exception/$exceptionView",
            [
                'statusCode' => $exception->getCode() ?? null,
            ],
        );

        $response = new Response($content);
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
