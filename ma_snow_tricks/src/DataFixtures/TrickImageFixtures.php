<?php

namespace App\DataFixtures;

use App\Entity\TrickImage;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\TrickFixtures;

class TrickImageFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $imageList = [
                        [
                        'name' => 'mute_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'mute',
                        ],
                        [
                        'name' => 'nose_grab_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'nose-grab',
                        ],
                        [
                        'name' => 'truck_driver_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'truck-driver',
                        ],
                        [
                        'name' => '1080_ou_big_foot_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => '1080-ou-big-foot',
                        ],
                        [
                        'name' => 'hakon_flip_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'hakon-flip',
                        ],
                        [
                        'name' => 'corkscrew_ou_cork_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'corkscrew-ou-cork',
                        ],
                        [
                        'name' => 'rocket_air_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'rocket-air',
                        ],
                        [
                        'name' => 'melancholie_image.jpeg',
                        'path' => '/upload',
                        'trick_slug' => 'melancholie',
                        ],
                        [
                        'name' => 'tail_grab_image.jpg',
                        'path' => '/upload',
                        'trick_slug' => 'tail-grab',
                        ],
                        [
                        'name' => 'japan_image.jpeg',
                        'path' => '/upload',
                        'trick_slug' => 'japan',
                        ],
                    ];

        foreach($imageList as $imageValue) {
            $trickImage = new TrickImage();
            $trickImage->setName($imageValue['name']);
            $trickImage->setPath($imageValue['path']);
            $trickImage->setTrick($this->getReference($imageValue['trick_slug'], Trick::class));
            $manager->persist($trickImage);
        }
            
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }
}