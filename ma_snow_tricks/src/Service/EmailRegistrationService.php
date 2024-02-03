<?php

namespace App\Service;

use App\Service\EmailService;

class EmailRegistrationService
{
    public function __construct(
        private EmailService $emailService,
    ) {
    }

    public function sendEmailRegistration(string $email, string $token): void
    {
        $routeName = 'confirm_registration';
        $valueName = 'registrationToken';
        $this->emailService->sendEmail($email, 'Confirmation d\'inscription', 'userRegistration.html.twig', $routeName, $valueName, $token);
    }
}