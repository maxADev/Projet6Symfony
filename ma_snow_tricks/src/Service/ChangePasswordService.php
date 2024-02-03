<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ChangePasswordService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function changePassword(User $user): void
    {
        $user->hashPassword();
        $user->removeResetPasswordToken();
        $user->removeResetPasswordTokenDate();
        $this->entityManager->flush();
    }
}
