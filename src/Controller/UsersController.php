<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users", methods={"GET"})
     */
    public function index(UserRepository $userRepo, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($userRepo->findAll(), 'json', [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'email',
                'avatar',
            ],
        ]);

        return $this->json($data);
    }
}
