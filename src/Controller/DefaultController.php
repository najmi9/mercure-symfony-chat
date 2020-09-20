<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mercure\Update;

use Symfony\Component\Mercure\PublisherInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function __invoke(MessageBusInterface $bus, PublisherInterface $pub)
    {
           $update = new Update(
  	        #topics
            [
                sprintf("http://monsite.com/ping"),
            ],
            #data
           json_encode([
               'message'=>'hello',
               'userId'=>'5',
               ]),
           # targets
           # [
           #    sprintf("/%s", $recipient->getEmail()),
           #   sprintf("/%s", $user->getEmail())
           #]
       );

        $pub->__invoke($update);

        //return $this->render('default/index.html.twig', [
        //    'controller_name' => 'DefaultController',
        //]);
        return $this->json(['ok'=>'ok']);
    }
}
