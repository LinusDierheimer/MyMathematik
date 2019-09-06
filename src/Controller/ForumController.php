<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{

    public function forum(Util $util)
    {
        return $this->render('site/forum/forum.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }
}