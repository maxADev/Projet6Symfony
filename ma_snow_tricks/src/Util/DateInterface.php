<?php

namespace App\Util;

interface  DateInterface
{
    public function setCreationDate(): static;

    public function getCreationDate(): ?\DateTimeInterface;

    public function setModificationDate(): static;

    public function getModificationDate(): ?\DateTimeInterface;
}
