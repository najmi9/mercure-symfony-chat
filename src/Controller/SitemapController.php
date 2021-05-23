<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('user_register')];
        $urls[] = ['loc' => $this->generateUrl('user_login')];


        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
