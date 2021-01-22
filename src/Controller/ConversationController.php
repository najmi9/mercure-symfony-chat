<?php

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
        //$conv = $convRepo->findOneByParticipants([$this->getUser(), $user]);
        if ($user === $this->getUser()) {
            return $this->json(['msg' => 'You can not create conversation with yourself.'], 400);
        }

        $conv = null;

        $convs = $convRepo->findAll();
        $ids = [];
        
        foreach ($convs as $cv) {
            foreach ($cv->getUsers()  as $u) {
                $ids[] = $u->getId();
            }
            if (in_array($user, $cv->getUsers()->getValues()) && in_array($this->getuser(), $cv->getUsers()->getValues())) {
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
        $conv->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->addUser($this->getUser())
            ->addUser($user);
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
        //$this->denyAccessUnlessGranted('CONV_VIEW', );
        // To Do SQL Improvments, findByUser.
        $convs = $convRepo->findAllConvs();

        $userConvs = [];
        foreach ($convs as $conv) {
            $users = $conv->getUsers()->getValues();
            $currentUser = $this->getUser();
            if (in_array($currentUser, $users)) {
                $c = [];
                $c['id'] = $conv->getId();
                $c['msg'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getContent(): 'Start Chat Now';
                $c['date'] = $conv->getLastMessage() !== null ? $conv->getLastMessage()->getUpdatedAt(): $conv->getUpdatedAt();
                
                foreach ($users as $user) {
                    if ($user != $currentUser) {
                        $c['user'] = [
                            'id' => $user->getId(),
                            'email' => $user->getemail(),
                            'name' => $user->getName(),
                            'avatar' => $user->getAvatar()
                        ];
                    }
                }
                $userConvs[] = $c;
            }
        }

        return $this->json($userConvs);
    }
}
