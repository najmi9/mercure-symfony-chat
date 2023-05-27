<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Message;
use App\Infrastructure\Mercure\Events\MercureEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MessageListener
{
    private SerializerInterface $serializer;

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        SerializerInterface $serializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
    }

    public function postPersist(Message $msg): void
    {
        $this->dispatcher->dispatch(
            new MercureEvent(
                ["/msgs/{$msg->getConversation()->getId()}"],
                $this->getData($msg)
            )
        );
    }

    public function postUpdate(Message $msg): void
    {
        $this->dispatcher->dispatch(
            new MercureEvent(
                ["/msgs/{$msg->getConversation()->getId()}"],
                $this->getData($msg, true)
            )
        );
    }

    private function getData(Message $msg, bool $update = false): array
    {
        $data = $this->serializer->serialize($msg, 'json', ['groups' => 'msg']);

        $data = json_decode($data, true);

        $data['isNew'] = !$update;

        return $data;
    }
}
