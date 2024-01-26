<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Group;
use DateTime;

class HomeController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {   
        return $this->render('home/homePage.html.twig');
    }

    public function showTrick(EntityManagerInterface $entityManager, Request $request): Response
    {
        $newTricklList = [];

        $nbTrick = $request->request->get('nbTrick');

        $repository = $entityManager->getRepository(Trick::class);
        $trickList = $repository->findBy([], ['id'=>'DESC'], 3, $nbTrick);

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

        $newNbTrick = $nbTrick + 3;

        return new JsonResponse(['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick]);
    }
}
