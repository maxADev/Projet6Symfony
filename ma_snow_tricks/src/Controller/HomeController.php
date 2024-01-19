<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PDO;
class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('home/homePage.html.twig');
    }
}
