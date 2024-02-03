<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmUserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CheckTokenService $checkTokenService
    ) {
    }

    public function userConfirm(User $user): void
    {

        $user->removeRegistrationToken();
        $user->removeRegistrationTokenDate();
        $user->setStatut(1);
        $this->entityManager->flush();
    }
}
