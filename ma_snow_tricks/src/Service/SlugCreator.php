<?php

namespace App\Service;

use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugCreator
{
    public function createSlug(string $str): string
    {
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($str)->lower();
        return $slug;
    }
}
