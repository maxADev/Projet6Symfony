<?php

// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('/')]
    public function index(EntityManagerInterface $entityManager): Response
    {   
        return $this->render('home/homePage.html.twig');
    }

    #[Route('/show')]
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $newTricklList = [];
        $getNbTrick = 5;

        $nbTrick = $request->request->get('nbTrick');
        if($nbTrick == 0)
        {
            $getNbTrick = 10;
        }

        $repository = $entityManager->getRepository(Trick::class);
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

        $newNbTrick = $nbTrick + 5;

        return new JsonResponse(['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick]);
    }

    #[Route('/trick/{slug}')]
    public function showTrick(Trick $trick): Response
    {
        return $this->render('trick/trickPage.html.twig', [
            'trick' => $trick,
        ]);
    }
}
