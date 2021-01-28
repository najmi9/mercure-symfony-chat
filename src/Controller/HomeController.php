<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Mercure\MercureCookieGenerator;

class HomeController extends AbstractController
{
    /**
     * @Route("/{r1}/{r2}", name="home", defaults={"r1": null, "r2": null})
     */
    public function index(MercureCookieGenerator $cookieGenerator): Response
    {
        $response =  $this->render("home/index.html.twig");
        $response->headers->setCookie($cookieGenerator->generate($this->getUser()));

        return $response;
    }
}
