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

    public function sendEmailRegistration(string $email, string $token): void
    {
        $routeName = 'confirm_registration';
        $valueName = 'registrationToken';
        $this->sendEmail($email, 'Confirmation d\'inscription', 'userRegistration.html.twig', $routeName, $valueName, $token);
    }

    public function sendEmail(string $userEmail, string $subject, string $template, string $routeName, string $valueName, string $value): void
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
