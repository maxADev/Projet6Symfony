<?php

namespace App\Service;

use DateTime;

class TokenService
{
    public function checkValidationToken(DateTime $token): bool
    {
        $tokenCreatedAt = $token;
        $checkTime = new \DateTime();
        $checkTime->modify("+15 minutes");
    
        if ($tokenCreatedAt <= $checkTime) {
            return true;
        } else {
            return false;
        }
    }
}
