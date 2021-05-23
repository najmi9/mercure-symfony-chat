<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/api/users", name="users_")
*/
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
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
}
