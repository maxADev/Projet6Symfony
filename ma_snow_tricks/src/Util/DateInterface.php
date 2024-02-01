<?php

namespace App\Util;

interface  DateInterface
{
    public function setCreationDate(): static;

    public function getCreationDate(): static;

    public function setModificationDate(): static;

    public function getModificationDate(): static;
}
