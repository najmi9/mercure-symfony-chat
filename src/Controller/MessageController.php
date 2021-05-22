<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api", name="messages_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/convs/{conv}/msgs", name="of_conv", methods={"GET"})
     */
    public function getMessages(Conversation $conv, MessageRepository $msgRepo): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        $msgs = $msgRepo->findLast15Message($conv, 15);

        return  $this->json(array_reverse($msgs), 200, [], [
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
    public function delete(Message $message, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('DELETE_MSG', $message, 'Only the admins or the message owner can delete it.');

        $em->remove($message);
        $em->flush();

        return $this->json([], 204);
    }

    /**
     * @Route("/messages/{id}/update", name="edit", methods={"PUT"})
     */
    public function edit(Request $request, Message $message, EntityManagerInterface $em): JsonResponse
    {
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
