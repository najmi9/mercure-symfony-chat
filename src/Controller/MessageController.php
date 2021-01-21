<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/convs/{conv}/msgs", name="messages_of_conv", methods={"GET"})
     */
    public function getMessages(Conversation $conv): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        $data = [];
        foreach ($conv->getMessages() as $msg) {
            /** @var Message $msg */
            $data[] = [
                'id' => $msg->getId(),
                'isMyMsg' => $msg->getUser() === $this->getUser(),
                'user' => [
                    'id' => $msg->getUser()->getId(),
                    'name' => $msg->getUser()->getName(),
                    'avatar' => $msg->getUser()->getAvatar(),
                    'email' => $msg->getUser()->getEmail(),
                ],
                'content' => $msg->getContent(),
                'updated' => $msg->getUpdatedAt(),
            ];
        }

        return $this->json($data);
    }

     /**
     * @Route("/convs/{id}/msgs/new", name="messages_new", methods={"POST"})
     */
    public function index(Conversation $conv, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        if (!$request->getContent()) {
            $this->json([], 400);
        }

        $message = new Message();
        
        $message->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser())
                ->setContent($request->getContent())
                ->setConversation($conv)
            ;

        $conv->setLastMessage($message)
                ->setUpdatedAt(new \DateTime())
            ;

        $em->persist($message);
        $em->persist($conv);
        $em->flush();

        return $this->json(['id' => $message->getId()]);
    }
}
