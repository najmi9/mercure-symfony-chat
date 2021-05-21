<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * Instead of directly storing a JWT token in the .env, we create a service that will provide the token used by the Publisher object.
 */
class JWTProvider
{
   /*  private string $mercure_secret;
    private string $subscriber_key;

    public function __construct(string $mercure_secret, string $subscriber_key)
    {
        $this->mercure_secret = $mercure_secret;
        $this->subscriber_key = $subscriber_key;
    }

    public function __invoke(): string
    {
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->mercure_secret));

        return $configuration->builder()
            ->withClaim('mercure', ['publish' => ['*']])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString()
        ;
    }

    public function getTokenForWaitingRoom(string $token): string
    {
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->subscriber_key));

        return $configuration->builder()
            ->withClaim('mercure', ['subscribe' => [
                "http://beta.gvetsoft.com/en/next-visit/{$token}",
            ]])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString()
        ;
    } */
}
