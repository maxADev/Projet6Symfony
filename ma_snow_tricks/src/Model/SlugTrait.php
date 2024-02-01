<?php

namespace App\Model;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait SlugTrait
{
    public function createSlug(string $str): string
    {
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($str)->lower();
        return $slug;
    }
}
