<?php

namespace App\Controller;

use App\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends AbstractController
{

    public function redirect_with_language(
        Request $request,
        $class
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $request->getLocale(),
            'class' => $class
        ]);
    }

    public function redirect_with_class(
        $language
    ){
        return $this->redirectToRoute('route_videos', [
            'language' => $language,
            'class' => $this->getParameter('default_class')
        ]);
    }

    public function list(
        Globals $util,
        Request $request,
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

        $open = 0;
        if($request->query->has("open"))
            $open = $request->query->get("open");

        return $this->render('site/videos/videolist.html.twig', [
            'video_language' => $language,
            'video_class'    => $class,
            'open'           => $open
        ]);
    }

    public function video(
        Globals $util,
        $language,
        $class,
        $chapter,
        $index
    ){

        //If somebody or old code i forgot about try to acces 0, redirect to 1
        if($chapter == 0)
            return $this->redirectToRoute('route_video', [
                'language' => $language,
                'class' => $class,
                'chapter' => $chapter + 1,
                'index' => $index
            ]);
        if($index == 0)
            return $this->redirectToRoute('route_video', [
                'language' => $language,
                'class' => $class,
                'chapter' => $chapter,
                'index' => $index + 1
            ]);

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
        if(!array_key_exists($chapter - 1, $current))
        {
            return $this->render('site/videos/errorChapter.html.twig', [
                'language' => $language,
                'class'    => $class,
                'chapter'  => $chapter - 1
            ]);
        }
        $current = $current[$chapter - 1];

        $current = $current["videos"];
        if(!array_key_exists($index - 1, $current))
        {
            return $this->render('site/videos/errorVideo.html.twig', [
                'language' => $language,
                'class'    => $class,
                'chapter'  => $chapter - 1,
                'index'    => $index - 1
            ]);
        }

        return $this->render('site/videos/video.html.twig', [
            'video_language' => $language,
            'video_class'    => $class,
            'chapter_index'  => $chapter - 1,
            'video_index'    => $index - 1
        ]);
    }
}