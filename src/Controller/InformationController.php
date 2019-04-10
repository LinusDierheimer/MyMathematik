<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InformationController extends AbstractController
{

    public function cookies(Util $util)
    {
        return $this->render('site/information/cookies.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function contact(Util $util)
    {
        return $this->render('site/information/contact.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function impressum(Util $util)
    {
        return $this->render('site/information/impressum.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function sponsors(Util $util)
    {
        return $this->render('site/information/sponsors.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function conditions(Util $util)
    {
        return $this->render('site/information/conditions.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

}