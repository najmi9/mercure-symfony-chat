<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
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
       );

         // Sync, or async (RabbitMQ, Kafka...)
        $bus->dispatch($update);
        return $this->json(['message'=>'published']);
    }
}

