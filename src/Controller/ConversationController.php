<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class ConversationController extends AbstractController
{
    /**
     * @Route("/conversation/{id}/index", name="conversation_index")
     */
    public function index(Conversation $conv, Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser())
                ->setConversation($conv)
            ;

            $conv->setLastMessage($message);

            $em->persist($message);
            $em->persist($conv);
            $em->flush();
        }

        return $this->render('conversation/index.html.twig', [
            'conv' => $conv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/conversation/new/{id}", name="conversation_new")
     */
    public function new(User $user, ConversationRepository $convRepo, EntityManagerInterface $em, MessageBusInterface $bus): Response
    {
        //$conv = $convRepo->findOneByParticipants([$this->getUser(), $user]);
        $conv = null;

        $convs = $convRepo->findAll();
        foreach ($convs as $cv) {
            if ($cv->getUsers()->getValues() == [$this->getUser(), $user]) {
                $conv = $cv;
                break;
            }
        }

        if ($conv) {
            return $this->redirectToRoute('conversation_index', ['id' => $conv->getId()]);
        }

        $conv = new Conversation();
        $conv->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->addUser($this->getUser())
            ->addUser($user)
        ;
        $em->persist($conv);
        $em->flush();
        
        $id = $this->getUser()->getId();

        $update = new Update(
            [
                "/convs/{$id}/other_user/{$user->getId()}",
                "http://convc.com/convs",
            ],
            json_encode([
                'id' => $conv->getId(),
                'otherUser' => $user->getEmail(),
                'createdAt' => $conv->getCreatedAt()
            ])
        );

        $bus->dispatch($update);

        return $this->redirectToRoute('conversation_index', ['id' => $conv->getId()]);
    }

    /**
     * @Route("/conversations", name="conversations")
     */
    public function convs(ConversationRepository $convRepo): Response
    {
        $convs = $convRepo->findAll();
        $userConvs = [];
        foreach ($convs as $conv) {
            $users = $conv->getUsers()->getValues();
            $currentUser = $this->getUser();
            if (in_array($currentUser, $users)) {
                $userConvs[] = $conv;
            }
        }
        //$convs = $convRepo->findByUser($this->getUser());

        return $this->render('conversation/convs.html.twig', [
            'convs' => $userConvs
        ]);
    }
}
