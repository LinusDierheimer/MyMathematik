<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Globals;

class VideoController extends AbstractController
{

    public function redirect_with_language(
        Globals $util,
        $class
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $util->current_language["code"],
            'class' => $class
        ]);
    }

    public function redirect_with_class(
        Globals $util,
        $language
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $language,
            'class' => $util->get_parameter('default_class')
        ]);
    }

    public function list(
        Globals $util,
        $language,
        $class
    ){

        $videoConfig = $util->videos;

        if(!array_key_exists($language, $videoConfig))
        {
            return $this->render('site/videos/errorLangauge.html.twig', [
                'language' => $language
            ]);
        }
    
        if(!array_key_exists($class, $videoConfig[$language]))
        {
            return $this->render('site/videos/errorClass.html.twig', [
                'language' => $language,
                'class'    => $class
            ]);
        }

        return $this->render('site/videos/videolist.html.twig', [
            'videoLanguage' => $language,
            'videoClass'    => $class
        ]);
    }

    public function video(
        Globals $util,
        $language,
        $class,
        $chapter,
        $index
    ){

        $current = $util->videos;

        if(!array_key_exists($language, $current))
        {
            return $this->render('site/videos/errorLanguage.html.twig', [
                'language' => $language
            ]);
        }
        $current = $current[$language];
    
        if(!array_key_exists($class, $current))
        {
            return $this->render('site/videos/errorClass.html.twig', [
                'language' => $language,
                'class'    => $class
            ]);
        }
        $current = $current[$class];

        $current = $current["chapters"];
        if(!array_key_exists($chapter, $current))
        {
            return $this->render('site/videos/errorChapter.html.twig', [
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
                'language' => $language,
                'class'    => $class,
                'chapter'  => $chapter,
                'index'    => $index
            ]);
        }

        return $this->render('site/videos/video.html.twig', [
            'language' => $language,
            'class'    => $class,
            'chapter'  => $chapter,
            'index'    => $index
        ]);
    }
}