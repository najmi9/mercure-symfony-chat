<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure\Events;

use App\Infrastructure\Mercure\Events\MercureEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class MercureSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;
    private HubInterface $hub;

    public function __construct(SerializerInterface $serializer, HubInterface $hub)
    {
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

        $update = new Update($channels, $this->serializer->serialize($data, 'json'), false /* true */);

        $this->hub->publish($update);
    }
}
