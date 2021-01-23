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

        $conv = $convRepo->findOneByParticipants($this->getUser(), $user);

        $convs = $convRepo->findAll();
        
        foreach ($convs as $cv) {
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
        $convs = $convRepo->findLast15ConvsOfUser($this->getUser(), 15);
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
                        'avatar' => $user->getAvatar()
                    ];
                }
            }
            $userConvs[] = $c;
        }
    
        return $this->json($userConvs);
    }
}
