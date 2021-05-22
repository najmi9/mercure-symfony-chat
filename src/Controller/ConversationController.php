<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api")
 */
class ConversationController extends AbstractController
{
    /**
     * @Route("/convs/new/{id}", name="conversation_new", methods={"POST", "GET"})
     */
    public function new(User $user, ConversationRepository $convRepo, EntityManagerInterface $em): JsonResponse
    {
        if ($user === $this->getUser()) {
            return $this->json(['msg' => 'You can not create conversation with yourself.'], 400);
        }

        $conv = null;
        $userConvs = $convRepo->findConvsOfUser($this->getUser());

        foreach ($userConvs as $cv) {
            if ($cv->getUsers()->contains($user)) {
                $conv = $cv;
                break;
            }
        }

        if ($conv) {
            return $this->json([
                'id' => $conv->getId(),
                'alreadyExists' => true,
            ]);
        }

        $conv = new Conversation();
        $conv->addUser($this->getUser())
            ->addUser($user)
            ->setOwnerId($this->getUser()->getId())
        ;
        $em->persist($conv);
        $em->flush();

        return $this->json([
            'id' => $conv->getId(), 
            'alreadyExists' => false,
        ]);
    }

    /**
     * @Route("/convs", name="conversations", methods={"GET"})
     */
    public function convs(ConversationRepository $convRepo): Response
    {
        $convs = $convRepo->findConvsOfUser($this->getUser(), /* limit */15);
        $userConvs = [];
        foreach ($convs as $conv) {
            $c = [];
            $c['id'] = $conv->getId();
            $c['msg'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getContent(): 'Start Chat Now';
            $c['date'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getUpdatedAt(): $conv->getUpdatedAt();
            
            foreach ($conv->getUsers() as $user) {
                if ($user != $this->getUser()) {
                    $c['user'] = [
                        'id' => $user->getId(),
                        'email' => $user->getemail(),
                        'name' => $user->getName(),
                        'picture' => $user->getPicture()
                    ];
                }
            }
            $userConvs[] = $c;
        }
    
        return $this->json($userConvs);
    }

    /**
     * @Route("/convs/{id}", name="conversation_show", methods={"GET"})
     */
    public function conv(Conversation $conv): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_VIEW', $conv);

        return $this->json($conv, 200, [], [
            'groups' => 'conv_show'
        ]);
    }

    /**
     * @Route("/convs/{id}/delete", name="conversation_delte", methods={"DELETE"})
     */
    public function delete(Conversation $conv, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('CONV_EDIT', $conv);

        try {
            $em->remove($conv);
            $em->flush();
        } catch (\Exception $e) {
            dd($e);
             return $this->json(['error' => 'Unexpected Error'], 500);
        }
        return $this->json([], 204);
    }
}
