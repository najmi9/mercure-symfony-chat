<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Message;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MessageListener
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    public function postPersist(Message $msg): void
    {
        $this->bus->dispatch($this->getUpdate($msg));
    }

    public function postUpdate(Message $msg): void
    {
        $this->bus->dispatch($this->getUpdate($msg));
    }

    private function getUpdate(Message $msg): Update
    {
        $data = $this->serializer->serialize($msg, 'json', ['groups' => 'msg']);

        return new Update(
            ["/msgs/{$msg->getConversation()->getId()}"],
            $data,
            //true
        );
    }
}
