<?php

namespace App\Util;

interface SlugInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): static;
}
