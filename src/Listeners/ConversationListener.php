<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Entity\Conversation;
use Symfony\Component\Mercure\Update;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ConversationListener
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

    public function postPersist(Conversation $conv, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($conv));
    }

    public function postUpdate(Conversation $conv, LifecycleEventArgs $event): void
    {
        $this->bus->dispatch($this->getUpdate($conv, false));
    }

    private function getUpdate(Conversation $conv, bool $isNew = true): Update
    {
        $currentUser = $this->security->getUser();
        $c = [];
        $c['new'] = $isNew ? true: false;
        $c['id'] = $conv->getId();
        $c['msg'] = $conv->getLastMessage() ==! null ?? $conv->getLastMessage()->getContent();
        $c['date'] = $conv->getLastMessage() ==! null ?? $conv->getLastMessage()->getUpdatedAt();

        foreach ($conv->getUsers() as $user) {
            if ($user != $currentUser) {
                $c['user']['id'] = $user->getId();
                $c['user']['email'] = $user->getEmail();
                $c['user']['name'] = $user->getName();
                $c['user']['avatar'] = $user->getAvatar();
            }
        }

        $data = $this->serializer->serialize($c, 'json');
        //$token = $nextVisit->getVeterinary()->getToken();

        return new Update(
            ["http://mywebsite.com/convs"],
            $data,
            //true
        );
    }
}
