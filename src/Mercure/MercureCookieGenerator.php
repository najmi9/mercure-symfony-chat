<?php

#src/Mercure/MercureCookieGenerator.php

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

        $token = $configuration->builder()
            ->withClaim('mercure', [
                'subscribe' => [
                "*",
            ]])
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
