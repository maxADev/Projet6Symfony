<?php

namespace App\Model;

use DateTime;

trait DateTrait
{
    public function createDateTime(): ?\DateTimeInterface
    {
        $date = new DateTime();
        return $date;
    }
}
