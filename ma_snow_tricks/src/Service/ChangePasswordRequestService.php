<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ChangePasswordRequestService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailService $emailService,
    ) {
    }

    public function requestChangePassword(string $email): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email, 'statut' => 1]);

        if (!$user) {
            return;
        }

        $user->generateResetPasswordToken();
        $user->setResetPasswordTokenDate();
        $this->entityManager->flush();

        $routeName = 'change_password';
        $valueName = 'resetPasswordToken';
        $this->emailService->sendEmail($user->getEmail(), 'Changer votre mot de passe', 'changePassword.html.twig', $routeName, $valueName, $user->getResetPasswordToken());
    }
}
