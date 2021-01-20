<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route(path="/message", name="message", methods={"POST", "GET"})
     */
    public function index(Request $request, MessageBusInterface $bus): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $update = new Update(
            #topics
            [
                sprintf("http://beta.gvetsoft.com/en/next-visit/{$user->getId()}"),
            ],
            #data
            json_encode([
                'message' => $data['message'],
            ]),
            //true
        );

        // Sync, or async (RabbitMQ, Kafka...)
        $bus->dispatch($update);
        return $this->json(['message' => 'published']);
    }

    /**
     * @Route("/conversation/{conv}/msgs", name="messages_of_conv", methods={"GET"})
     */
    public function getMessages(Conversation $conv): JsonResponse
    {
        //
        // ToDo
        // Not anyone can access to the msgs
        // Just only who a participiant in the conversation
        //
        $data = [];
        $msgs = $conv->getMessages();
        foreach ($msgs as $msg) {
            /** @var Message $msg */
            $data[] = [
                'id' => $msg->getId(),
                'isMyMsg' => $msg->getUser() === $this->getUser(),
                'user' => [
                    'id' => $msg->getUser()->getId(),
                    'name' => $msg->getUser()->getName(),
                    'avatar' => $msg->getUser()->getAvatar(),
                    'email' => $msg->getUser()->getEmail()
                ],
                'content' => $msg->getContent(),
                'updated' => $msg->getUpdatedAt()

            ];
        }
        return $this->json($data);
    }
}
