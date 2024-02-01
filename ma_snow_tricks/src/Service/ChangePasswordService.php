<?php

namespace App\Service;

use App\Entity\User;

class ChangePasswordService
{
    public function changePassword(User $user): void
    {
        $user->hashPassword();
        $user->removeResetPasswordToken();
    }
}
