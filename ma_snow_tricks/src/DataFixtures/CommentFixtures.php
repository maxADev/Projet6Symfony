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
use DateTime;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $date = new DateTime();

        $user = $manager->getRepository(User::class)->findOneBy(['name' => 'user0']);

        $comment_list = [
                        [
                        'content' => 'Très bien',
                        'trick_slug' => 'mute',
                        ],
                        [
                        'content' => 'Vraiment très impressionnant',
                        'trick_slug' => 'mute',
                        ],
                        [
                        'content' => 'C\'est très risqué',
                        'trick_slug' => 'mute',
                        ],
                        [
                        'content' => 'Bravo',
                        'trick_slug' => '1080-ou-big-foot',
                        ],
                        [
                        'content' => 'La classe',
                        'trick_slug' => 'hakon-flip',
                        ],
                        [
                        'content' => 'Je ne le ferait pas',
                        'trick_slug' => 'corkscrew-ou-cork',
                        ],
                        [
                        'content' => 'C\'est ouff',
                        'trick_slug' => 'rocket-air',
                        ],
                        [
                        'content' => 'Très fort, bravo',
                        'trick_slug' => 'melancholie',
                        ],
                        [
                        'content' => 'Quelle figure de fou',
                        'trick_slug' => 'tail-grab',
                        ],
                        [
                        'content' => 'Attention à la chute',
                        'trick_slug' => 'japan',
                        ],
                    ];

        foreach($comment_list as $commentValue) {

            $trick = $manager->getRepository(Trick::class)->findOneBy(['slug' => $commentValue['trick_slug']]);

            $comment = new Comment();
            $comment->setContent($commentValue['content']);
            $comment->setCreationDate($date);
            $comment->setUser($user);
            $comment->setTrick($trick);
            $manager->persist($user);
            $manager->persist($trick);
            $manager->persist($comment);
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