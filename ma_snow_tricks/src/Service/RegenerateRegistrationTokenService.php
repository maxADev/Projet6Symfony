<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegenerateRegistrationTokenService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailService $emailService,
        private EmailRegistrationService $emailRegistrationService,
    ) {
    }

    public function regenerateRegistrationToken($email): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return;
        }

        $user->generateRegistrationToken();
        $user->setRegistrationTokenDate();
        $this->entityManager->flush();

        $this->emailRegistrationService->sendEmailRegistration($user->getEmail(), $user->getRegistrationToken());
    }
}