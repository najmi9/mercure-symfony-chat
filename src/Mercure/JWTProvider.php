<?php

#src/Mercure/JWTProvider.php

namespace App\Mercure;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

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
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->secret));

        return $configuration->builder()
            ->withClaim('mercure', [
                'publish' => [
                    //"http://beta.gvetsoft.com/en/next-visit/{$user->getId()}",
                    '*'
                ]
            ])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString()
        ;
    }
}
