<?php

namespace App\Util;

interface  DateInterface
{
    public function setCreationDate(\DateTimeInterface $creation_date): static;

    public function getModificationDate(): ?\DateTimeInterface;
}
