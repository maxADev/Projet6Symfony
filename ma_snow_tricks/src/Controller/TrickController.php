<?php

// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Trick;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TrickService;
use App\Form\Type\TrickType;

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {   
        return $this->render('home/homePage.html.twig');
    }

    #[Route('/show')]
    public function show(Request $request, TrickService $trickService): Response
    {
        $user = $this->getUser();
        $nbTrick = intval($request->request->get('nbTrick'));
        $trickListValue = $trickService->getTrickList($nbTrick, $user);

        return new JsonResponse(['listTrick' => $trickListValue['listTrick'], 'nbTrick' => $trickListValue['nbTrick']]);
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function showTrick(Trick $trick): Response
    {
        return $this->render('trick/trickPage.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[Route('/create-trick', name: 'create_trick')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createTrick(Request $request, TrickService $trickService): Response
    {
        $user = $this->getUser();
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $imageUpload = $form->get('image')->getData();
           $trick->setUser($user);
           $trickService->createTrick($trick, $imageUpload);
           return $this->redirectToRoute('home');
        }

        return $this->render('trick/createTrick.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/modify-trick/{slug}', name: 'modify_trick')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function modifyTrick(Request $request, Trick $trick, TrickService $trickService): Response
    {
        $user = $this->getUser();

        if ($user->getId() === $trick->getUser()->getId()) {
            $form = $this->createForm(TrickType::class, $trick);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $imageUpload = $form->get('image')->getData();
                $trickService->updateTrick($trick, $imageUpload);
                return $this->redirectToRoute('home');
            }

            return $this->render('trick/updateTrick.html.twig', [
                'trick' => $trick,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
