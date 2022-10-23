<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure\Events;

use App\Infrastructure\Mercure\Events\MercureEvent;
use App\Infrastructure\Queue\EnqueueMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class MercureSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;
    private HubInterface $hub;

    public function __construct(EnqueueMethod $enqueue, SerializerInterface $serializer, HubInterface $hub)
    {
        $this->enqueue = $enqueue;
        $this->serializer = $serializer;
        $this->hub = $hub;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MercureEvent::class => 'publishNotification',
        ];
    }

    public function publishNotification(MercureEvent $event): void
    {
        $channels = $event->getChannels();
        $data = $event->getData();

        $update = new Update($channels, $this->serializer->serialize($data, 'json'));

        $this->hub->publish($update);
    }
}
