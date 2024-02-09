<?php

namespace App\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait SlugTrait
{
    #[ORM\Column(length: 250)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $this->createSlug($slug);

        return $this;
    }

    public function createSlug(string $str): string
    {
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($str)->lower();
        return $slug;
    }
}
