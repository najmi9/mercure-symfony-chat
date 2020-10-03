<?php

#src/Mercure/JWTProvider.php

namespace App\Mercure;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Builder;

class JWTProvider
{
	 private $secret;
	 //récupérer les utilisateur et faire le publish en fonction
	 function __construct(string $secret)
	  {
	  	$this->secret = $secret;
	  } 

	 public function __invoke(): string
    {
         return (new Builder())
            ->set('mercure', ['publish'=>
            	[
            	"*"
                ]
            ])
            ->sign(new Sha256(), $this->secret)
            ->getToken()
         ;
    }
}