<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util;

class VideoController extends AbstractController
{

    public function redirect_with_language($class)
    {
        return $this->redirectToRoute('route_videos', [
            'language' => Util::get_language_code(),
            'class' => $class
        ]);
    }

    public function redirect_with_class($language)
    {
        return $this->redirectToRoute('route_videos', [
            'language' => $language,
            'class' => Util::get_default_class()
        ]);
    }

    public function index($language, $class)
    {

        if(!Util::class_exist($class))
            return $this->render('videos404.html.twig', [
                'classes' => Util::get_classes(),
                'informations' => Util::get_informations()
            ]);

        return $this->render('videos.html.twig', [
            'class' => $class,
            'videos' => Util::load_videos($class),
            'classes' => Util::get_classes(),
            'informations' => Util::get_informations()
        ]);
    }
}