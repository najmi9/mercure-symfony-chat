<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Conversation;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ConversationListener
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    public function postPersist(Conversation $conv): void
    {
        $this->bus->dispatch($this->getUpdate($conv));
    }

    public function postUpdate(Conversation $conv): void
    {
        $this->bus->dispatch($this->getUpdate($conv, false));
    }

    private function getUpdate(Conversation $conv, bool $isNew = true): Update
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
                'avatar' => $user->getAvatar(),
            ];
        }
        
        $data = $this->serializer->serialize($c, 'json');
        $targets = [];
        foreach ($conv->getUsers() as $user) {
            $targets[] = "http://mywebsite.com/convs/{$user->getId()}";
        }

        return new Update(
            $targets,
            $data,
            //true
        );
    }
}
