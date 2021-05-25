<?php

declare(strict_types=1);

namespace App\Command;

use App\Infrastructure\Notification\EmailNotifierInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendEmailCommand extends Command
{
    private EmailNotifierInterface $emailNotifier;

    public function __construct(EmailNotifierInterface $emailNotifier)
    {
        parent::__construct();
        $this->emailNotifier = $emailNotifier;
    }

    protected static $defaultName = 'send:email';
    protected static $defaultDescription = 'Send Emails';

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');
        $question = new Question('Enter the receiver email:  ');

        $receiver = $helper->ask($input, $output, $question);

        $email = $this->emailNotifier->createEmail('Test Subject', 'emails/test.txt', []);

        $email->to($receiver);

        $this->emailNotifier->sendNow($email);

        $io->success('Email Sent successfully.');

        return Command::SUCCESS;
    }
}
