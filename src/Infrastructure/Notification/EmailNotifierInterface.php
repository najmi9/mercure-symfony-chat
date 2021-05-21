<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use Symfony\Component\Mime\Email;

interface EmailNotifierInterface
{
    /**
     * Send an email asynchronounsly.
     *
     * @param Email $email the email to send
     */
    public function send(Email $email): void;

    /**
     * Sign the email and send it right now(synchronounsly).
     *
     * @see https://symfony.com/doc/current/mailer.html#dkim-signer
     * @see https://en.wikipedia.org/wiki/DomainKeys_Identified_Mail
     *
     * @param Email $email the email to send
     */
    public function sendNow(Email $email): void;

    /**
     * Create a new Email.
     *
     * @param string $subject  Email Subejct
     * @param string $template Email Tepmplate
     * @param array  $context  Needed variables in the template
     *
     * @return Email an instance of the Email to send
     */
    public function createEmail(string $subject, string $template, array $context = []): Email;
}
