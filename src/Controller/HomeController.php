<?php

declare(strict_types=1);

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
    public function index(MercureCookieGenerator $cookieGenerator, UserRepository $user): Response
    {
        $response =  $this->render("home/index.html.twig");
        $response->headers->setCookie($cookieGenerator->generate($this->getUser()));

        return $response;
    }
}
