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
    public function index(Request $request)
    {
        return $this->render('admin/admin.html.twig', [
            'globals' => Util::get_globals()
        ]);
    }

    public function videoconfig(Request $request)
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

        return $this->render('admin/videoconfig.html.twig', [
            'globals' => Util::get_globals(),
            'content' => Util::get_video_config_content(),
            'videofiles' => Util::get_video_files(),
        ]);
    }

    public function doaction(Request $request)
    {
        return $this->json(Util::doaction($request->query));
    }

}