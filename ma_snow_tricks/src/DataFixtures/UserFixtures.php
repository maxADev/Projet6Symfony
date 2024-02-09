<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName('user'.$i);
            $user->setEmail('email'.$i.'@gmail.com');
            $user->setPassword('ijrgfeoijgeroiergj');
            $date = new DateTime();
            $user->setCreationDate($date);
            $user->setRegistrationToken($this->randomToken(15));
            $user->setStatut(0);
            $user->setCgu(true);
            $user->setCguDate($date);
            $manager->persist($user);
            $this->addReference('User'.'_'.$i, $user);
        }

        $manager->flush();
    }

    public function randomToken(string $length): string
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);

    }
}
