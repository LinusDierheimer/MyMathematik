<?php

namespace App\Controller;

use App\Util;
use App\Form\VideoUploadType;
use App\Entity\UploadedVideo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/*
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    public function index(Request $request)
    {
        $video = new UploadedVideo();

        $form = $this->createForm(VideoUploadType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $video->getVideo();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter("path_test"),
                    $fileName
                );
            } catch (FileException $e) {
                throw e;
            }

            return $this->redirectToRoute("route_home");
        }

        return $this->render('admin/admin.html.twig', [
            'globals' => Util::get_globals(),
            'form' => $form->createView()
        ]);
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}