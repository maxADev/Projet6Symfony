<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordService
{
    public function __construct(
        private UserRepository $userRepository,
        private FlashMessageService $flashMessageService,
        private UserPasswordHasherInterface $passwordHasher,
        private EmailService $emailService,
    ) {
    }

    public function requestChangePassword(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email, 'statut' => 1]);

        if (!$user) {
            return;
        }

        $user->generateResetPasswordToken();
        $user->setResetPasswordTokenDate();
        $this->userRepository->save($user);

        $routeName = 'change_password';
        $valueName = 'resetPasswordToken';
        $this->emailService->sendEmail($user->getEmail(), 'Changer votre mot de passe', 'changePassword.html.twig', $routeName, $valueName, $user->getResetPasswordToken());
        $this->flashMessageService->createFlashMessage('success', 'Un email vous a été envoyé pour changer votre mot de passe');
    }

    public function changePassword(User $user): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword(),
        );
        $user->setPassword($hashedPassword);
        $user->removeResetPasswordToken();
        $user->removeResetPasswordTokenDate();
        $this->userRepository->save($user);
        $this->flashMessageService->createFlashMessage('success', 'Votre mot de passe a bien été changé');
    }
}
