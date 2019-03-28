<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(Util $util)
    {
        return $this->render('site/home/home.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }
}