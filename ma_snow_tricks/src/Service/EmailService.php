<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private UrlGeneratorInterface $router,
    ) {
    }

    public function sendEmail(String $userEmail, String $subject, String $template, $routeName, $valueName, $value): void
    {

        $url = $this->router->generate($routeName, [
            $valueName => $value,
        ]);

        $email = (new TemplatedEmail())
        ->to($userEmail)
        ->subject($subject)
        ->htmlTemplate('emails/'.$template)
        ->context([
            'link' => $url,
        ]);
        $this->mailer->send($email);
    }
}
