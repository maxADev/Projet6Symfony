<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $group_list = [
                       'Les grabs',
                       'Les rotations',
                       'Les flips',
                       'Les rotations désaxées',
                       'Old school',
                      ];

        foreach($group_list as $groupValue) {
            $group = new Group();
            $group->setName($groupValue);
            $group->setSlug($this->createSlug($groupValue));
            $manager->persist($group);
        }
            
        $manager->flush();
    }

    public function createSlug($name)
    {
        return $this->noAccent(strtolower(str_replace([' ', "'"], '-', $name)));
    }

    public function noAccent($value) {
        $from = array(
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ',
            'ß', 'ç', 'Ç',
            'è', 'é', 'ê', 'ë',
            'È', 'É', 'Ê', 'Ë',
            'ì', 'í', 'î', 'ï',
            'Ì', 'Í', 'Î', 'Ï',
            'ñ', 'Ñ',
            'ò', 'ó', 'ô', 'õ', 'ö',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö',
            'š', 'Š',
            'ù', 'ú', 'û', 'ü',
            'Ù', 'Ú', 'Û', 'Ü',
            'ý', 'Ý', 'ž', 'Ž'
           );
          
        $to = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 
            'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'B',  'c', 'C',
            'e', 'e', 'e', 'e',
            'E', 'E', 'E', 'E',
            'i', 'i', 'i', 'i',
            'I', 'I', 'I', 'I', 
            'n',  'N',
            'o', 'o', 'o', 'o', 'o',
            'O', 'O', 'O', 'O', 'O', 
            's',  'S', 
            'u', 'u', 'u', 'u', 
            'U', 'U', 'U', 'U', 
            'y',  'Y', 'z', 'Z'
           );

        return str_replace($from, $to, $value);
    }
}