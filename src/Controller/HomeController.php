<?php

#src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Mercure\MercureCookieGenerator;
use App\Repository\UserRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MercureCookieGenerator $cookieGenerator, UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        $response =  $this->render("home/index.html.twig", ['users' => $users]);
        
        $response->headers->setCookie($cookieGenerator->generate($this->getUser()));
        return $response;
    }
}
