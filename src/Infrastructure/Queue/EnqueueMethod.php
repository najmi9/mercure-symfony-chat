<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue;

use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Execute a method of a service asynchronounsly.
 */
class EnqueueMethod
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Dispatch a Message in the MessageBus.
     *
     * @param string             $service The name of the class that conatins a method to be executed
     * @param string             $method  the method of the class to be executed asynchronounsly
     * @param array              $params  method parameters
     * @param \DateTimeInterface $date    Date To send execute the service
     */
    public function enqueue(string $service, string $method, array $params = []): void
    {
        $this->bus->dispatch(new ServiceMethodMessage($service, $method, $params));
    }
}
