<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InformationController extends AbstractController
{

    public function cookies()
    {
        return $this->render('site/information/cookies.html.twig');
    }

    public function contact()
    {
        return $this->render('site/information/contact.html.twig');
    }

    public function impressum()
    {
        return $this->render('site/information/impressum.html.twig');
    }

    public function sponsors()
    {
        return $this->render('site/information/sponsors.html.twig', [
            "sponsors" => Yaml::parseFile($this->getParameter('file_sponsors'))
        ]);
    }

    public function conditions()
    {
        return $this->render('site/information/conditions.html.twig');
    }

}