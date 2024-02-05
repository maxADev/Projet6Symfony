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
use App\Service\LoadTrickService;

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {   
        return $this->render('home/homePage.html.twig');
    }

    #[Route('/show')]
    public function show(Request $request, LoadTrickService $loadTrickService): Response
    {
        $nbTrick = intval($request->request->get('nbTrick'));
        $trickListValue = $loadTrickService->getTrickList($nbTrick);

        return new JsonResponse(['listTrick' => $trickListValue['listTrick'], 'nbTrick' => $trickListValue['nbTrick']]);
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function showTrick(Trick $trick): Response
    {
        return $this->render('trick/trickPage.html.twig', [
            'trick' => $trick,
        ]);
    }
}
