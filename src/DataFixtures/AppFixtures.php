<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();

        $user1->setPassword($this->encoder->hashPassword($user1, '123456'))
            ->setName('John Doe')
            ->setEmail('john@doe.com')
            ->setIsEnabled(true)
        ;

        $user2 = new User();

        $user2->setPassword($this->encoder->hashPassword($user2, '123456'))
            ->setName('Sofia Broke')
            ->setEmail('sofia@broke.com')
            ->setIsEnabled(true)
        ;

        $user3 = new User();

        $user3->setPassword($this->encoder->hashPassword($user3, '123456'))
            ->setName('Bernar Magali')
            ->setEmail('bernar@magali.com')
            ->setIsEnabled(true)
        ;

        $user4 = new User();

        $user4->setPassword($this->encoder->hashPassword($user4, '123456'))
            ->setName('Vetrina Rami')
            ->setEmail('vetrina@rami.com')
            ->setIsEnabled(true)
        ;

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);

        $manager->flush();
    }
}
