<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('site/home/home.html.twig', [
            "sponsors" => Yaml::parseFile($this->getParameter('file_sponsors'))
        ]);
    }
}