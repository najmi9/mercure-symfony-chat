<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Message;
use Symfony\Component\Mercure\Update;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class MessageListener
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    public function postPersist(Message $msg, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($msg));
    }

    public function postUpdate(Message $msg, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($msg));
    }

    private function getUpdate(Message $msg): Update
    {
        $data = $this->serializer->serialize($msg, 'json', [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'created',
                'updated',
                'content',
                'conversation' => ['id'],
                'user' => ['name', 'id', 'email'],
            ],
        ]);
        //$token = $nextVisit->getVeterinary()->getToken();

        return new Update(
            ["http://mywebsite.com/msg"],
            $data,
            //true
        );
    }
}
