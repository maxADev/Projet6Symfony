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

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
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

        if($nbTrick == 0)
        {
            $nbTrick = 5;
        }

        $newNbTrick = $nbTrick + 5;

        return new JsonResponse(['listTrick' => $newTricklList, 'nbTrick' => $newNbTrick]);
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
