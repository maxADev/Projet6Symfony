<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $groupList = [
                       'Les grabs',
                       'Les rotations',
                       'Les flips',
                       'Les rotations desaxees',
                       'Old school',
                      ];

        foreach($groupList as $groupValue) {
            $group = new Group();
            $group->setName($groupValue);
            $group->setSlug($this->createSlug($groupValue));
            $manager->persist($group);
        }
            
        $manager->flush();
    }

    public function createSlug(string $name): string
    {
        return strtolower(str_replace([' ', "'"], '-', $name));
    }
}