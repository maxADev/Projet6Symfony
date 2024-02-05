<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangePasswordService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FlashMessageService $flashMessageService,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
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
        $this->entityManager->flush();
        $this->flashMessageService->createFlashMessage('success', 'Votre mot de passe a bien été changé');
    }
}
