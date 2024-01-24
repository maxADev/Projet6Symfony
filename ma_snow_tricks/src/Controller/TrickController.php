<?php

// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;

class TrickController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager, string $slug): Response
    {
        $repository = $entityManager->getRepository(Trick::class);
        $trick = $repository->findOneBy(['slug' => $slug]);

        return $this->render('trick/trickPage.html.twig', [
            'trick' => $trick,
        ]);
    }
}
