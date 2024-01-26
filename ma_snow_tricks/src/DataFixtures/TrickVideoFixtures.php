<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\TrickFixtures;
use App\Entity\TrickVideo;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $videoList = [
                        [
                        'link' => 'https://www.youtube.com/watch?v=jm19nEvmZgM',
                        'trick_slug' => 'mute',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=M-W7Pmo-YMY',
                        'trick_slug' => 'nose-grab',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=Vz0qu5AfDTg',
                        'trick_slug' => 'truck-driver',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=Vz0qu5AfDTg',
                        'trick_slug' => '1080-ou-big-foot',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=QF2rtZBsjIo',
                        'trick_slug' => 'hakon-flip',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=P5ZI-d-eHsI',
                        'trick_slug' => 'corkscrew-ou-cork',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=nom7QBoGh5w',
                        'trick_slug' => 'rocket-air',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=KEdFwJ4SWq4',
                        'trick_slug' => 'melancholie',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=id8VKl9RVQw',
                        'trick_slug' => 'tail-grab',
                        ],
                        [
                        'link' => 'https://www.youtube.com/watch?v=jH76540wSqU',
                        'trick_slug' => 'japan',
                        ],
                    ];

        foreach($videoList as $videoValue) {
            $trickVideo = new TrickVideo();
            $trickVideo->setLink($videoValue['link']);
            $trickVideo->setTrick($this->getReference($videoValue['trick_slug'], Trick::class));
            $manager->persist($trickVideo);
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