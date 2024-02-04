<?php

namespace App\Service;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class TrickListService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getTrickList(string $nbTrick): Array
    {
        $newTricklList = [];
        $getNbTrick = 5;

        if($nbTrick == 0)
        {
            $getNbTrick = 10;
        }

        $repository = $this->entityManager->getRepository(Trick::class);
        $trickList = $repository->findBy([], ['id'=>'DESC'], $getNbTrick, $nbTrick);

        foreach ($trickList as $trick) {
            $newTricklList[$trick->getId()]['name'] = $trick->getName();
            $newTricklList[$trick->getId()]['slug'] = $trick->getSlug();
            $listTrickImages = $trick->getTrickImages();
            foreach ($listTrickImages as $trickImages) {
                if (empty($newTricklList[$trick->getId()]['image']))
                {
                    $newTricklList[$trick->getId()]['image'] = $trickImages->getName();
                }
            }
        }

        if($nbTrick == 0)
        {
            $nbTrick = 5;
        }

        $newNbTrick = $nbTrick + 5;

        $trickList = ['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick];

        return $trickList;
    }
}
