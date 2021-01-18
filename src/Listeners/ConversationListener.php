<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Conversation;
use Symfony\Component\Mercure\Update;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ConversationListener
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    public function postPersist(Conversation $conv, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($conv));
    }

    public function postUpdate(Conversation $conv, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($conv));
    }

    private function getUpdate(Conversation $conv): Update
    {
        $data = $this->serializer->serialize($conv, 'json', [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'created',
                'updated',
                'lastMessage' => ['content'],
                'users' => ['name', 'id', 'email'],
            ],
        ]);
        //$token = $nextVisit->getVeterinary()->getToken();

        return new Update(
            ["http://mywebsite.com/convs"],
            $data,
            //true
        );
    }
}
