<?php

#src/Mercure/MercureCookieGenerator.php

namespace App\Mercure;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureCookieGenerator
{
	private $secret;
	function __construct(string $secret)
	{
		$this->secret = $secret;
	}

	public function generate(): string
	{
         $token = (new Builder())
          ->set('mercure', ['subscribe'=>["*"]])
          ->sign(new Sha256(), $this->secret)
          ->getToken()
         ;
         return "mercureAuthorization={$token}; Path=/.well-known/mercure; HttpOnly";
	}
}