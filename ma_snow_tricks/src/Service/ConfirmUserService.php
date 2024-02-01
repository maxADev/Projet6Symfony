<?php

namespace App\Service;

use App\Entity\User;

class ConfirmUserService
{
    public function userConfirm(User $user): void
    {
        $user->removeRegistrationToken();
        $user->setStatut(1);
    }
}
