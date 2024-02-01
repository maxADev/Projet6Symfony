<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function sendEmail(String $userEmail, String $subject, String $content): void {
        $email = (new Email())
        ->to($userEmail)
        ->subject($subject)
        ->html($content);
        $this->mailer->send($email);
    }
}
