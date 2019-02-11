<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util;

class VideoController extends AbstractController
{
    public function index($class)
    {
        if($class < 0)
            return $this->redirectToRoute('route_videos', ['class' => 5]);
        
        return $this->render('videos.html.twig', [
            'title' => "MyMathematik - Videos - " . $class,
            'class' => $class,
            'videos' => Util::load_videos($class)
        ]);
    }
}