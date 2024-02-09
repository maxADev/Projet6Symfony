<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailService $emailService,
        private FlashMessageService $flashMessageService,
        private UserPasswordHasherInterface $passwordHasher,
        
    ) {
    }

    public function userRegister(User $user): void
    {
        $user->generateRegistrationToken();
        $user->setRegistrationTokenDate();
        $user->setCguDate();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword(),
        );
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailService->sendEmailRegistration($user->getEmail(), $user->getRegistrationToken());
        $this->flashMessageService->createFlashMessage('success', 'Un email vous a été envoyé pour valider votre compte');
    }
}
