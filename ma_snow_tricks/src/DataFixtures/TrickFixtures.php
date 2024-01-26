<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\GroupFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTime;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $date = new DateTime();

        $trickList = [
                        [
                         'name' => 'mute',
                         'description' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                         'group' => 'les-grabs',
                        ],
                        [
                         'name' => 'nose grab',
                         'description' => 'saisie de la partie avant de la planche, avec la main avant',
                         'group' => 'les-grabs',
                        ],
                        [
                         'name' => 'truck driver',
                         'description' => 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)',
                         'group' => 'les-grabs',
                        ],
                        [
                         'name' => '1080 ou big foot',
                         'description' => 'faire trois tours',
                         'group' => 'les-rotations',
                        ],
                        [
                         'name' => 'Hakon Flip',
                         'description' => 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière',
                         'group' => 'les-flips',
                        ],
                        [
                         'name' => 'corkscrew ou cork',
                         'description' => 'Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation',
                         'group' => 'les-rotations-desaxees',
                        ],
                        [
                         'name' => 'rocket air',
                         'description' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                         'group' => 'old-school',
                        ],
                        [
                         'name' => 'melancholie',
                         'description' => ' saisie de la carre backside de la planche, entre les deux pieds, avec la main avant',
                         'group' => 'les-grabs',
                        ],
                        [
                         'name' => 'tail grab',
                         'description' => 'saisie de la partie arrière de la planche, avec la main arrière',
                         'group' => 'les-grabs',
                        ],
                        [
                         'name' => 'japan',
                         'description' => 'saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside',
                         'group' => 'les-grabs',
                        ],
                      ];

        foreach($trickList as $trickValue) {

            $trick = new Trick();
            $trick->setName($trickValue['name']);
            $trick->setDescription($trickValue['description']);
            $trick->setUser($this->getReference('User_0', User::class));
            $trick->setTrickGroup($this->getReference($trickValue['group'], Group::class));

            $manager->persist($trick);
            
            $this->addReference($trick->getSlug(), $trick);
        }
        $manager->flush();
    }

    public function createSlug(string $name): string
    {
        return strtolower(str_replace([' ', "'"], '-', $name));
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            GroupFixtures::class,
        ];
    }
}