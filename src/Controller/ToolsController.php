<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToolsController extends AbstractController
{

    public function tools()
    {
        return $this->render('site/tools/tools.html.twig');
    }

    public function calculator()
    {
        return $this->render('site/tools/calculator.html.twig');
    }
}