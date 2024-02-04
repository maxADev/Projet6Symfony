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
use App\Repository\CommentRepository;
use App\Service\TrickListService;

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {   
        return $this->render('home/homePage.html.twig');
    }

    #[Route('/show')]
    public function show(Request $request, TrickListService $trickListService): Response
    {
        $nbTrick = $request->request->get('nbTrick');
        $trickListValue = $trickListService->getTrickList($nbTrick);

        return new JsonResponse(['listTrick' => $trickListValue['listTrick'], 'nbTrick' => $trickListValue['nbTrick']]);
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function showTrick(Request $request, Trick $trick, CommentRepository $commentRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($trick, $offset);

        return $this->render('trick/trickPage.html.twig', [
            'trick' => $trick,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]);
    }
}
