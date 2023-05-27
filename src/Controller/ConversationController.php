<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Infrastructure\Mercure\Events\MercureEvent;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api/conversations", name="conversation_")
 */
class ConversationController extends AbstractController
{
    private const MAX_CONVERSATIONS = 15;

    private const FIRST_MESSAGE = 'Start new chat';

    /**
     * @Route("/new/{id}", name="new", methods={"POST"})
     */
    public function new(
        User $user,
        ConversationRepository $conversationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $authenticatedUser = $this->getUser();
        if ($user === $this->getUser()) {
            return $this->json(
                ['msg' => 'You can not create conversation with yourself.'],
                400
            );
        }

        $conversation = $conversationRepository->findConversationByUsers([
            $authenticatedUser, $user
        ]);

        if ($conversation instanceof Conversation) {
            return $this->json([
                'id' => $conversation->getId(),
                'alreadyExists' => true,
            ]);
        }

        $conversation = new Conversation();
        $conversation->addUser($authenticatedUser)
            ->addUser($user)
            ->setOwnerId($authenticatedUser->getId())
        ;
        $entityManager->persist($conversation);
        $entityManager->flush();

        return $this->json([
            'id' => $conversation->getId(),
            'alreadyExists' => false,
        ]);
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(
        Request $request,
        ConversationRepository $conversationRepository
    ): JsonResponse {
        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', self::MAX_CONVERSATIONS);

        if ($max > self::MAX_CONVERSATIONS) {
            $max = self::MAX_CONVERSATIONS;
        }
        $authenticatedUser = $this->getUser();

        $offset = $currentPage * $max - $max;

        $conversations = $conversationRepository
            ->findByUser($authenticatedUser, $max, $offset);

        $userConversations = [];
        foreach ($conversations as $conversation) {
            $lastMessage = $conversation->getLastMessage();
            $item = [];
            $item['id'] = $conversation->getId();
            $item['ownerId'] = $conversation->getOwnerId();
            $item['msg'] = $lastMessage ? $lastMessage->getContent() : self::FIRST_MESSAGE;
            $item['date'] = $lastMessage ? $lastMessage->getUpdatedAt() : $conversation->getUpdatedAt();

            foreach ($conversation->getUsers() as $user) {
                if ($user !== $authenticatedUser) {
                    $item['user'] = [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'name' => $user->getName(),
                        'picture' => $user->getPicture()
                    ];
                }
            }
            $userConversations[] = $item;
        }

        return $this->json([
            'data' => $userConversations,
            'count' => (int) $conversationRepository->countByUser($authenticatedUser),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Conversation $conversation): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conversation);

        return $this->json($conversation, 200, [], [
            'groups' => 'conv_show'
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     */
    public function delete(
        Conversation $conversation,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CONV_DELETE', $conversation);
        $targets = [];

        foreach ($conversation->getUsers() as $user) {
            $targets[] = "/convs/{$user->getId()}";
        }

        $dispatcher->dispatch(new MercureEvent($targets, [
            'id' => $conversation->getId(),
            'isDeleted' => true,
            ])
        );

        try {
            $entityManager->remove($conversation);
            $entityManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => 'Unexpected Error'], 500);
        }

        return $this->json([], 204);
    }
}
