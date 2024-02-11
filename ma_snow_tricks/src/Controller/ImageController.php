<?php

// src/Controller/ImageController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TrickImage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\ORM\EntityManagerInterface;

class ImageController extends AbstractController
{
    #[Route('/delete-image/{id}', name: 'delete_image')]
    public function deleteImage(Request $request, TrickImage $trickImage, EntityManagerInterface $entityManager): JsonResponse
    {
        $token = $request->request->get('token');
        if($this->isCsrfTokenValid('delete'.$trickImage->getId(), $token)){
            $imageName = $trickImage->getName();
            unlink('upload/'.$imageName);
            $entityManager->remove($trickImage);
            $entityManager->flush();
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token invalide']);
        }
    }
}
