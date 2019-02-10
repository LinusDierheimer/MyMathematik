<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('home.html.twig', [
            'title' => "Mein Titel"
        ]);
    }
}