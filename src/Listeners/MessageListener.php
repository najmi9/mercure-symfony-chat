<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Message;
use Symfony\Component\Mercure\Update;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class MessageListener
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;
    private Security $security;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer, Security $security)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
        $this->security = $security;
    }

    public function postPersist(Message $msg, LifecycleEventArgs $event): void
    {
        //$this->bus->dispatch($this->getUpdate($msg));
    }

    public function postUpdate(Message $msg, LifecycleEventArgs $event): void
    {
        //$this->bus->dispatch($this->getUpdate($msg));
    }

    private function getUpdate(Message $msg): Update
    {
        $data = [
            'id' => $msg->getId(),
            'isMyMsg' => $msg->getUser() === $this->security->getUser(),
            'user' => [
                'id' => $msg->getUser()->getId(),
                'name' => $msg->getUser()->getName(),
                'avatar' => $msg->getUser()->getAvatar(),
                'email' => $msg->getUser()->getEmail()
            ],
            'content' => $msg->getContent(),
            'updated' => $msg->getUpdatedAt(),
        ];

        $data = $this->serializer->serialize($data, 'json');
        

        return new Update(
            ["http://mywebsite.com/msg/{$msg->getConversation()->getId()}"],
            $data,
            //true
        );
    }
}
