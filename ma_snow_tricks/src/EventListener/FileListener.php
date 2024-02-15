<?php

namespace App\EventListener;

use App\Entity\TrickImage;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;

#[AsDoctrineListener('preRemove')]
class FileListener
{
    public function __construct(
        private Filesystem $filesystem
    ) {
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof TrickImage) {
            return;
        }

        $imageName = $entity->getName();
        if ($this->filesystem->exists('upload/'.$imageName)) {
            unlink('upload/'.$imageName);
        }
    }
}