<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @IsGranted("ROLE_USER")
 * @Route("/api")
*/
class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users", methods={"GET"})
     */
    public function index(UserRepository $userRepo): JsonResponse
    {
        $users = [];
        foreach($userRepo->findAll() as $u) {
            if ($u !== $this->getUser()) {
                $users[] = [
                    'id' => $u->getId(),
                    'name' => $u->getName(),
                    'avatar' => $u->getAvatar(),
                    'email' => $u->getEmail(),
                ];
            }
        }

        return $this->json($users);
    }
}
