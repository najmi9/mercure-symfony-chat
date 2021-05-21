<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\Subscriber;

use App\Infrastructure\Notification\EmailNotifier;
use App\Infrastructure\Notification\EmailNotifierInterface;
use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

class FailedMessageSubscriber implements EventSubscriberInterface
{
    private EmailNotifierInterface $emailNotifier;
    private string $admin_email;

    public function __construct(EmailNotifierInterface $emailNotifier, string $admin_email)
    {
        $this->emailNotifier = $emailNotifier;
        $this->admin_email = $admin_email;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed',
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $message = $event->getEnvelope()->getMessage();
        // If the failed message is an email notification, we don't send a new one(this will create infinite loop).
        if ($message instanceof ServiceMethodMessage && EmailNotifier::class === $message->getServiceName()) {
            return;
        }

        $message = \get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();

        $email = $this->emailNotifier->createEmail($message, 'emails/worker_failed.html.twig', [
            'msg' => $message,
            'trace' => $trace,
        ])->to($this->admin_email);

        $this->emailNotifier->send($email);
    }
}
