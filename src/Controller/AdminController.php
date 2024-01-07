<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(UserRepository $userRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepo->findAll(),
        ]);
    }
}
