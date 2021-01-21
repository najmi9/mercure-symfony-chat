<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Conversation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
}
