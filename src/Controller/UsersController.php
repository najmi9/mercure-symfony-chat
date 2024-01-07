<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/users', name: 'users_')]
#[IsGranted('ROLE_USER')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepo): JsonResponse
    {
        $currentPage = $request->query->getInt('page', 1);
        $max = $request->query->getInt('max', 15);

        $offset = $currentPage * $max - $max;

        return $this->json([
            'data' => $userRepo->findLast15Users($this->getUser(), $max, $offset),
            'count' => $userRepo->count([]),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'User deleted');

        return $this->redirectToRoute('admin_users');
    }
}
