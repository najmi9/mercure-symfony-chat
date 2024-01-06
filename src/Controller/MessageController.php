<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Infrastructure\Mercure\Events\MercureEvent;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'messages_')]
#[IsGranted('ROLE_USER')]
class MessageController extends AbstractController
{
    private const MAX_CONVERSATIONS = 15;

    #[Route('/conversations/{id}/msgs', name: 'conversation', methods: ['GET'])]
    public function getMessages(
        Request $request,
        Conversation $conversation,
        MessageRepository $messageRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conversation);

        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', self::MAX_CONVERSATIONS);
        if ($max > self::MAX_CONVERSATIONS) {
           $max = self::MAX_CONVERSATIONS;
        }
        $offset = $currentPage * $max - $max;

        $msgs = $messageRepository->findLastMessages($conversation, $max, $offset);

        return $this->json(
            [
                'data' => $msgs,
                'count' => (int) $messageRepository->countByConversation($conversation),
            ],
            200,
            [],
            ['groups' => ['msg']]
        );
    }

     #[Route('/conversations/{id}/msgs/new', name: 'new', methods: ['POST'])]
    public function new(
        Conversation $conversation,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conversation);

        if (true === empty($request->getContent())) {
            $this->json([
                'msg' => 'Message content required',
            ], 400);
        }

        $message = new Message();
        $message
            ->setUser($this->getUser())
            ->setContent($request->getContent())
            ->setConversation($conversation)
        ;

        $conversation->setLastMessage($message);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json(['id' => $message->getId()]);
    }

    #[Route('/messages/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(
        Message $message,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): JsonResponse {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted(
            'DELETE_MSG',
            $message,
            'Only the admins or the message owner can delete it.'
        );
        $event = new MercureEvent(
            ["/msgs/{$message->getConversation()->getId()}"],
            [
                'id' => $message->getId(),
                'isDeleted' => true,
            ]
        );
        $dispatcher->dispatch($event);

        $entityManager->remove($message);
        $entityManager->flush();

        return $this->json([], 204);
    }

    #[Route('/messages/{id}/update', name: 'edit', methods: ['PUT'])]
    public function edit(
        Request $request,
        Message $message,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted(
            'EDIT_MSG',
            $message,
            'Only the admins or the message owner can edit it.'
        );
        $content = $request->getContent();

        if (empty($content)) {
            return $this->json(['msg' => 'Content Required'], 400);
        }

        $message->setContent($content);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json(
            $message,
            200,
            [],
            ['groups' => 'msg']
        );
    }
}
