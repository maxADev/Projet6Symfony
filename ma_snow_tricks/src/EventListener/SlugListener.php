<?php

namespace App\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use App\Util\SlugInterface;

#[AsDoctrineListener('prePersist'/*, 500, 'default'*/)]
#[AsDoctrineListener('preUpdate'/*, 500, 'default'*/)]
class SlugListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
       
        if (!$entity instanceof SlugInterface) {
            return;
        }

        $entity->setSlug($entity->getName());
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
       
        if (!$entity instanceof SlugInterface) {
            return;
        }

        $entity->setSlug($entity->getName());
    }
}
