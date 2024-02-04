<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailRegistrationService $emailRegistrationService,
        private FlashMessageService $flashMessageService,
    ) {
    }

    public function userRegister(User $user): void
    {
        $user->generateRegistrationToken();
        $user->setRegistrationTokenDate();
        $user->setStatut(0);
        $user->setCguDate();
        $user->hashPassword();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailRegistrationService->sendEmailRegistration($user->getEmail(), $user->getRegistrationToken());
        $this->flashMessageService->createFlashMessage('success', 'Un email vous a été envoyé pour valider votre compte');
    }
}
