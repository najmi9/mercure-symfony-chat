<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Conversation;
use App\Infrastructure\Mercure\Events\MercureEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ConversationListener
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, SerializerInterface $serializer)
    {
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
    }

    public function postPersist(Conversation $conv): void
    {
        $this->pushData($conv);
    }

    public function postUpdate(Conversation $conv): void
    {
        $this->pushData($conv, false);
    }

    private function pushData(Conversation $conv, bool $isNew = true): void
    {
        // Do not Display my name on any conversation.
        $c = [];
        $c['new'] = $isNew;
        $c['id'] = $conv->getId();
        $c['msg'] = $conv->getLastMessage() != null ? $conv->getLastMessage()->getContent() : 'Start The Chat Now';
        $c['date'] = $conv->getLastMessage() != null ? $conv->getLastMessage()->getUpdatedAt() : $conv->getUpdatedAt();

        $c['users'] = [];
        foreach ($conv->getUsers() as $user) {
            $c['users'][] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'picture' => $user->getPicture(),
            ];
        }

        $c['ownerId'] = $conv->getOwnerId();

        $targets = [];

        foreach ($conv->getUsers() as $user) {
            $targets[] = "/convs/{$user->getId()}";
        }

        $this->dispatcher->dispatch(new MercureEvent($targets, $c));
    }
}
