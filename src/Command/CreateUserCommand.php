<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    private UserPasswordEncoderInterface $encoder;
    private EntityManagerInterface $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected static $defaultName = 'create:user';
    protected static $defaultDescription = 'Create new user.';

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');
        $question = new Question('Enter the user email:  ');

        $email = $helper->ask($input, $output, $question);

        $password = $helper->ask($input, $output, (new Question('Enter the password: '))->setHidden(true));
        $name = $helper->ask($input, $output, new Question('Enter the username: '));
        $user = new User();
      
        $user->setEmail($email)
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setName($name)
        ;

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User created successfully.');

        return Command::SUCCESS;
    }
}
