<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * Instead of directly storing a JWT token in the .env, we create a service that will provide the token used by the Publisher object.
 * use a subscriber key and a publisher key
 */
class JWTProvider
{
    private string $secret;

    /* public function __construct(string $secret)
    {
        $this->secret = $secret;
    } */

    public function __invoke(): string
    {
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->secret));

        return $configuration->builder()
            ->withClaim('mercure', [
                'publish' => [
                    '*',
                ]
            ])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString()
        ;
    }
}
