<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route(path="/message", name="message", methods={"POST", "GET"})
     */
    public function index(Request $request, MessageBusInterface $bus):Response
    {
           $data = json_decode($request->getContent(), true);
         
           $update = new Update(
  	        #topics
            [
                sprintf("http://monsite.com/ping"),
            ],
            #data
           json_encode([
               'message'=>$data['message'],
               ]),
           # targets
           # [
           #    sprintf("/%s", $recipient->getEmail()),
           #   sprintf("/%s", $user->getEmail())
           #]
       );

         // Sync, or async (RabbitMQ, Kafka...)
        $bus->dispatch($update);
        return $this->json(['message'=>'published']);
    }

    /**
     * @Route(path="/", name="home", methods={"GET"})
    */
   
   public function home():Response
   {
      return $this->render("default/index.html.twig");
   }
    
}
