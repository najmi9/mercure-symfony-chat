<?php

declare(strict_types=1);

namespace App\Mercure;

use App\Entity\User;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\HttpFoundation\Cookie;

class MercureCookieGenerator
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(User $user): Cookie
    {
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->secret));

        $convs = $user->getConversations()->getValues();

        $targets = [];
        // I can subscribe just for my convs
        $targets[] = "/convs/{$user->getId()}";

        foreach ($convs as $conv) {
            $targets[] = "/msgs/{$conv->getId()}";
        }

        $token = $configuration->builder()
            ->withClaim('mercure', [
                'subscribe' => $targets,
                'publish' => $targets,
            ])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString()
        ;

        return Cookie::create('mercureAuthorization')
            ->withValue($token)
            ->withPath('/.well-known/mercure')
            ->withSecure(true)
            ->withHttpOnly(true)
            ->withSameSite('strict')
        ;
    }
}
