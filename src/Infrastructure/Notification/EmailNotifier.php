<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Infrastructure\Queue\EnqueueMethod;
use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Crypto\DkimSigner;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailNotifier implements EmailNotifierInterface
{
    private MailerInterface $mailer;
    private string $admin_email;
    private Environment $twig;
    // private string $dkimKey;
    private EnqueueMethod $enqueue;

    public function __construct(MailerInterface $mailer, string $admin_email, Environment $twig, /* string $dkimKey,  */EnqueueMethod $enqueue)
    {
        $this->mailer = $mailer;
        $this->admin_email = $admin_email;
        $this->twig = $twig;
        // $this->dkimKey = $dkimKey;
        $this->enqueue = $enqueue;
    }

    public function createEmail(string $subject, string $template, array $data = []): Email
    {
        $email = new Email();
        $html = $this->twig->render($template, $data);

        $email->from($this->admin_email)
            ->html($html)
            ->subject($subject)
        ;

        return $email;
    }

    public function send(Email $email): void
    {
        $this->enqueue->enqueue(self::class, 'sendNow', [$email]);
    }

    public function sendNow(Email $email): void
    {
        /* if ($this->dkimKey) {
            $pk = file_get_contents($this->dkimKey);
            if ($pk) {
                $dkimSigner = new DkimSigner($pk, $this->parameters->getDomain(), 'default');
                $email = $dkimSigner->sign($email, []);
            }
        } */

        $this->mailer->send($email);
    }
}
