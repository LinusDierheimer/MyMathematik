<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToolsController extends AbstractController
{
    public function calculator(Util $util)
    {
        return $this->render('site/tools/calculator.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }
}