<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Infrastructure\Notification\EmailNotifierInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener implements EventSubscriberInterface
{
    private EmailNotifierInterface $emailNotifier;
    private string $admin_email;

    public function __construct(EmailNotifierInterface $emailNotifier, string $admin_email)
    {
        $this->emailNotifier = $emailNotifier;
        $this->admin_email = $admin_email;
    }

    public function onException(ExceptionEvent $event): void
    {
        $msg = $event->getThrowable()->getMessage();
        $email = $this->emailNotifier->createEmail("Error {$msg}", 'emails/error.html.twig', [
            'uri' => $event->getRequest()->getRequestUri(),
            'msg' => $msg,
            'trace' => $event->getThrowable()->getTraceAsString(),
        ]);

        $email->to($this->admin_email);

        $this->emailNotifier->sendNow($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }
}