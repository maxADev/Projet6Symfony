<?php

namespace App\EventListener;

use App\Entity\Trick;
use App\Entity\Group;
use App\Service\DateCreator;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener('prePersist'/*, 500, 'default'*/)]
class DateListener
{
    private ?DateCreator $dateCreator;

    public function __construct()
    {
        $this->dateCreator = new DateCreator();
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $checkClass = get_class($entity);
        $classList = ['App\Entity\TrickImage', 'App\Entity\TrickVideo', 'App\Entity\Group'];
     
        if (in_array($checkClass, $classList)) {
            return;
        }

        $entity->setCreationDate($this->dateCreator->getDate());
    }
}
