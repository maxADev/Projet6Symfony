<?php

namespace App\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use App\Util\DateInterface;

#[AsDoctrineListener('prePersist'/*, 500, 'default'*/)]
#[AsDoctrineListener('preUpdate'/*, 500, 'default'*/)]
class DateListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof DateInterface) {
            return;
        }

        $entity->setCreationDate($entity->createDateTime());
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
       
        if (!$entity instanceof DateInterface) {
            return;
        }

        $entity->setModificationDate($entity->createDateTime());
    }
}
