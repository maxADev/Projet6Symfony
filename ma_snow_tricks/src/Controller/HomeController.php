<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;

class HomeController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {
        $image_list = [
            [
            'name' => 'mute_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'mute',
            ],
            [
            'name' => 'nose_grab_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'nose-grab',
            ],
            [
            'name' => 'truck_driver_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'truck-driver',
            ],
            [
            'name' => '1080_ou_big_foot_image.jpg',
            'path' => '/upload',
            'trick_slug' => '1080-ou-big-foot',
            ],
            [
            'name' => 'hakon_flip_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'hakon-flip',
            ],
            [
            'name' => 'corkscrew_ou_cork_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'corkscrew-ou-cork',
            ],
            [
            'name' => 'rocket_air_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'rocket-air',
            ],
            [
            'name' => 'melancholie_image.jpeg',
            'path' => '/upload',
            'trick_slug' => 'melancholie',
            ],
            [
            'name' => 'tail_grab_image.jpg',
            'path' => '/upload',
            'trick_slug' => 'tail-grab',
            ],
            [
            'name' => 'japan_image.jpeg',
            'path' => '/upload',
            'trick_slug' => 'japan',
            ],
        ];

        foreach($image_list as $imageValue) {

            $trick = $entityManager->getRepository(Trick::class)->findOneBy(['slug' => $imageValue['trick_slug']]);

            echo $trick->getId().'<br>';
        }

        return $this->render('home/homePage.html.twig');
    }
}
