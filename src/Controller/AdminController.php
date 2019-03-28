<?php

namespace App\Controller;

use App\Util;
use App\Form\VideoUploadType;
use App\Entity\UploadedVideo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/*
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    public function index(Util $util, Request $request)
    {
        return $this->render('site/admin/admin.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function videoconfig(Util $util, Request $request)
    {

        if($request->request->has("videoUploadForm"))
        {   
            $request->files->get("videoUploadForm")["file"]->move(
                $this->getParameter("videos_directory"),
                $request->request->get("videoUploadForm")["name"]
            );
            return $this->redirectToRoute("route_admin_videoconfig"); //reset POST request and load page ith GET
        }

        if($request->request->has("configFileForm"))
        {
            file_put_contents(
                $this->getParameter("videos_directory") . "/index.yaml",
                $request->request->get("configFileForm")["text"]

            );
            return $this->redirectToRoute("route_admin_videoconfig"); //reset POST request and load page ith GET
        }

        return $this->render('site/admin/videoconfig.html.twig', [
            'globals' => $util->get_globals(),
            'content' => $util->get_video_config_content(),
            'videofiles' => $util->get_video_files(),
        ]);
    }

    public function languageconfig(Util $util)
    {
        return $this->render('site/admin/languageconfig.html.twig', [
            'globals' => $util->get_globals(),
            'languages_content' => $util->get_languages_content(),
            'all_translations_content' => $util->get_all_translations_content()
        ]);
    }

    public function doaction(util $util, Request $request)
    {
        return $this->json($util->doaction($request->query));
    }

}