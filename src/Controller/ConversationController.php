<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/convs/{id}/msgs/new", name="conversation_index", methods={"POST"})
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
                //->setOtherUserId($user->getId())
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
        foreach ($convs as $cv) {
            if ($cv->getUsers()->getValues() === [$this->getUser(), $user]) {
                $conv = $cv;
                break;
            }
        }
 
        if ($conv) {
            return $this->json([
                'id' => $conv->getId(),
                'alreadyExists' => true,
                'otherUserId' => $user->getId(),
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
            'otherUserId' => $user->getId(),
        ]);
    }

    /**
     * @Route("/convs", name="conversations", methods={"GET"})
     */
    public function convs(ConversationRepository $convRepo): Response
    {
        //$this->denyAccessUnlessGranted('CONV_VIEW', );
        // To Do SQL Improvments, findByUser.
        $convs = $convRepo->findAll();
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
        //$convs = $convRepo->findByUser($this->getUser());


        return $this->json($userConvs);
    }
}
