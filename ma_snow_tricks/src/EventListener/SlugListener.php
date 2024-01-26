<?php

namespace App\EventListener;

use App\Entity\Trick;
use App\Entity\Group;
use App\Service\SlugCreator;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener('prePersist'/*, 500, 'default'*/)]
class SlugListener
{
    private ?SlugCreator $slugCreator;

    public function __construct()
    {
        $this->slugCreator = new SlugCreator();
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $checkClass = get_class($entity);
        $classList = ['App\Entity\Trick', 'App\Entity\Group'];
     
        if (!in_array($checkClass, $classList)) {
            return;
        }
        
        $entity->setSlug($this->slugCreator->createSlug($entity->getName()));
    }
}
