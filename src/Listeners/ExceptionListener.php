<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Infrastructure\Notification\EmailNotifierInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener implements EventSubscriberInterface
{
    private EmailNotifierInterface $emailNotifier;

    public function __construct(EmailNotifierInterface $emailNotifier)
    {
        $this->emailNotifier = $emailNotifier;
    }

    public function onException(ExceptionEvent $event): void
    {
        if ($event->getThrowable()->getStatusCode() >= 500) {
            $msg = $event->getThrowable()->getMessage();
            $email = $this->emailNotifier->createEmail("Error {$msg}", 'emails/error.html.twig', [
                'uri' => $event->getRequest()->getRequestUri(),
                'msg' => $msg,
                'trace' => $event->getThrowable()->getTraceAsString(),
            ]);

            $email->to('imad@najmidev.tech');

            $this->emailNotifier->sendNow($email);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }
}