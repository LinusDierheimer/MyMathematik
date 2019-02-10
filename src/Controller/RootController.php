<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RootController extends AbstractController
{
    public function index()
    {
        return $this->render('root.html.twig', [
            'title' => "Mein Titel"
        ]);
    }
}