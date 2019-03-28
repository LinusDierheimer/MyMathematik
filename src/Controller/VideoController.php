<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util;

class VideoController extends AbstractController
{

    public function redirect_with_language(Util $util, $class)
    {
        return $this->redirectToRoute('route_videos', [
            'language' => $util->get_language_code(),
            'class' => $class
        ]);
    }

    public function redirect_with_class(Util $util, $language)
    {
        return $this->redirectToRoute('route_videos', [
            'language' => $language,
            'class' => $util->get_default_class()
        ]);
    }

    public function index(Util $util, $language, $class)
    {

        if(!$util->class_exist($class))
            return $this->render('videos/videos404.html.twig', [
                'globals' => $util->get_globals()
            ]);

        return $this->render('site/videos/videos.html.twig', [
            'globals' => $util->get_globals(),
            'class' => $class,
            'videos' => $util->get_videos($class)
        ]);
    }
}