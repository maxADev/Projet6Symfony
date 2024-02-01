<?php

namespace App\Service;

use App\Entity\User;

class RegistrationService
{
    public function userRegister(User $user): void
    {
        $user->generateRegistrationToken();
        $user->setRegistrationTokenDate();
        $user->setStatut(0);
        $user->setCguDate();
        $user->hashPassword();
    }
}
