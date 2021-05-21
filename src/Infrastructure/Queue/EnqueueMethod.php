<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue;

use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

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
    public function enqueue(string $service, string $method, array $params = [], \DateTimeInterface $date = null): void
    {
        $stamps = [];
        // The service must be called after a delay.
        if (null !== $date) {
            $delay = 1000 * ($date->getTimestamp() - time());
            if ($delay > 0) {
                $stamps[] = new DelayStamp($delay);
            }
        }
        $this->bus->dispatch(new ServiceMethodMessage($service, $method, $params), $stamps);
    }
}
