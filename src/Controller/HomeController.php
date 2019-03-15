<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('home/home.html.twig', [
            'globals' => Util::get_globals()
        ]);
    }
}