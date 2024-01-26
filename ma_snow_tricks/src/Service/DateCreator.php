<?php

namespace App\Service;

use DateTime;

class DateCreator
{
    public function getDate(): ?\DateTimeInterface
    {
        $date = new DateTime();
        return $date;
    }
}
