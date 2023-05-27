<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Conversation;
use App\Infrastructure\Mercure\Events\MercureEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConversationListener
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher
    ) {
        $this->dispatcher = $dispatcher;
    }

    public function postPersist(Conversation $conversation): void
    {
        $this->pushData($conversation);
    }

    public function postUpdate(Conversation $conversation): void
    {
        $this->pushData($conversation, false);
    }

    private function pushData(Conversation $conversation, bool $isNew = true): void
    {
        $lastMessage = $conversation->getLastMessage();
        $data = [];
        $data['new'] = $isNew;
        $data['id'] = $conversation->getId();
        $data['msg'] = $lastMessage ? $lastMessage->getContent() : 'Start The Chat Now';
        $data['date'] = $lastMessage ? $lastMessage->getUpdatedAt() : $conversation->getUpdatedAt();

        $data['users'] = [];
        foreach ($conversation->getUsers() as $user) {
            $data['users'][] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'picture' => $user->getPicture(),
            ];
        }

        $data['ownerId'] = $conversation->getOwnerId();

        $targets = [];

        foreach ($conversation->getUsers() as $user) {
            $targets[] = "/convs/{$user->getId()}";
        }

        $this->dispatcher->dispatch(new MercureEvent($targets, $data));
    }
}
