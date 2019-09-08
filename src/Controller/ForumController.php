<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{

    public function forum()
    {
        return $this->render('site/forum/forum.html.twig');
    }
}