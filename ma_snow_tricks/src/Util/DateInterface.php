<?php

namespace App\Util;
use DateTime;

interface DateInterface
{
    public function setCreationDate(\DateTimeInterface $creationDate): static;

    public function getCreationDate(): ?\DateTimeInterface;

    public function setModificationDate(\DateTimeInterface $modificationDate): static;

    public function getModificationDate(): ?\DateTimeInterface;
}
