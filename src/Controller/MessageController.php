<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Infrastructure\Mercure\Events\MercureEvent;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api", name="messages_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/convs/{conv}/msgs", name="of_conv", methods={"GET"})
     */
    public function getMessages(Request $request, Conversation $conv, MessageRepository $msgRepo): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', 15);
        if ($max > 15) {
           $max = 15;
        }
        $offset = $currentPage * $max - $max;

        $msgs = $msgRepo->findLastMessages($conv, $max, $offset);

        return  $this->json([
            'data' => array_reverse($msgs),
            'count' => (int) $msgRepo->countMessages($conv),
        ], 200, [], [
            'groups' => [
                'msg'
            ],
        ]);
    }

     /**
     * @Route("/convs/{id}/msgs/new", name="new", methods={"POST"})
     */
    public function new(Conversation $conv, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        if (!$request->getContent()) {
            $this->json([], 400);
        }

        $message = new Message();
        $message
            ->setUser($this->getUser())
            ->setContent($request->getContent())
            ->setConversation($conv)
        ;

        $conv->setLastMessage($message);

        $em->persist($message);
        $em->persist($conv);
        $em->flush();

        return $this->json(['id' => $message->getId()]);
    }

    /**
     * @Route("/messages/{id}/delete", name="delete", methods={"DELETE"})
     */
    public function delete(Message $message, EntityManagerInterface $em, EventDispatcherInterface $dispatcher): JsonResponse
    {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted('DELETE_MSG', $message, 'Only the admins or the message owner can delete it.');

        $dispatcher->dispatch(new MercureEvent(["/msgs/{$message->getConversation()->getId()}"], [
            'id' => $message->getId(),
            'isDeleted' => true,
            ])
        );

        $em->remove($message);
        $em->flush();

        return $this->json([], 204);
    }

    /**
     * @Route("/messages/{id}/update", name="edit", methods={"PUT"})
     */
    public function edit(Request $request, Message $message, EntityManagerInterface $em): JsonResponse
    {
        /**
         * @todo Notify the conversation that the last message is updated.
         */
        $this->denyAccessUnlessGranted('EDIT_MSG', $message, 'Only the admins or the message owner can edit it.');
        $content = $request->getContent();

        if (empty($content)) {
            return $this->json(['msg' => 'Content Required'], 400);
        }

        $message->setContent($content);

        $em->persist($message);
        $em->flush();

        return $this->json($message, 200, [], [
            'groups' => 'msg',
        ]);
    }
}
