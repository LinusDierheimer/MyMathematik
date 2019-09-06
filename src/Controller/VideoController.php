<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Util;

class VideoController extends AbstractController
{

    public function redirect_with_language(
        Util $util,
        $class
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $util->get_current_language()["code"],
            'class' => $class
        ]);
    }

    public function redirect_with_class(
        Util $util,
        $language
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $language,
            'class' => $util->get_parameter('default_class')
        ]);
    }

    public function list(
        Util $util,
        $language,
        $class
    ){

        $videoConfig = $util->get_videos();

        if(!array_key_exists($language, $videoConfig))
        {
            return $this->render('site/videos/errorLangauge.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language
            ]);
        }
    
        if(!array_key_exists($class, $videoConfig[$language]))
        {
            return $this->render('site/videos/errorClass.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language,
                'class'    => $class
            ]);
        }

        return $this->render('site/videos/videolist.html.twig', [
            'globals'       => $util->get_globals(),
            'videoLanguage' => $language,
            'videoClass'    => $class
        ]);
    }

    public function video(
        Util $util,
        $language,
        $class,
        $chapter,
        $index
    ){

        $current = $util->get_videos();

        if(!array_key_exists($language, $current))
        {
            return $this->render('site/videos/errorLanguage.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language
            ]);
        }
        $current = $current[$language];
    
        if(!array_key_exists($class, $current))
        {
            return $this->render('site/videos/errorClass.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language,
                'class'    => $class
            ]);
        }
        $current = $current[$class];

        $current = $current["chapters"];
        if(!array_key_exists($chapter, $current))
        {
            return $this->render('site/videos/errorChapter.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language,
                'class'    => $class,
                'chapter'  => $chapter
            ]);
        }
        $current = $current[$chapter];

        $current = $current["videos"];
        if(!array_key_exists($index, $current))
        {
            return $this->render('site/videos/errorVideo.html.twig', [
                'globals'  => $util->get_globals(),
                'language' => $language,
                'class'    => $class,
                'chapter'  => $chapter,
                'index'    => $index
            ]);
        }

        return $this->render('site/videos/video.html.twig', [
            'globals'  => $util->get_globals(),
            'language' => $language,
            'class'    => $class,
            'chapter'  => $chapter,
            'index'    => $index
        ]);
    }
}