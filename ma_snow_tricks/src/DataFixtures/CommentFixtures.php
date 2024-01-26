<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\TrickFixtures;
use App\DataFixtures\UserFixtures;
use App\Factory\CommentFactory;
use DateTime;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $date = new DateTime();

        $commentList = [
                        [
                        'trick_slug' => 'mute',
                        ],
                        [
                        'trick_slug' => 'mute',
                        ],
                        [
                        'trick_slug' => 'mute',
                        ],
                        [
                        'trick_slug' => '1080-ou-big-foot',
                        ],
                        [
                        'trick_slug' => 'hakon-flip',
                        ],
                        [
                        'trick_slug' => 'corkscrew-ou-cork',
                        ],
                        [
                        'trick_slug' => 'rocket-air',
                        ],
                        [
                        'trick_slug' => 'melancholie',
                        ],
                        [
                        'trick_slug' => 'tail-grab',
                        ],
                        [
                        'trick_slug' => 'japan',
                        ],
                    ];

        foreach($commentList as $commentValue) {
            CommentFactory::createMany(5, ['user' => $this->getReference('User_0', User::class), 'trick' => $this->getReference($commentValue['trick_slug'], Trick::class)]);
        }
            
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TrickFixtures::class,
        ];
    }
}