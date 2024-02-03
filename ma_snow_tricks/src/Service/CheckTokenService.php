<?php

namespace App\Service;

use DateTime;

class CheckTokenService
{
    public function checkValidationToken(DateTime $token): bool
    {
        $return = true;
        $tokenCreatedAt = $token;
        $checkTime = new \DateTime();
        $difference = $tokenCreatedAt->diff($checkTime);
        $differenceValue = intval($difference->format('%I') + ($difference->format('%H') * 60));

        if ($differenceValue > 15) {
           $return = false;
        }

        return $return;
    }
}
