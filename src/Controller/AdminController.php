<?php

namespace App\Controller;

use App\Util;
use App\Form\VideoUploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/*
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    public function index(Request $request)
    {

        $form = $this->createForm(VideoUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = "/public/videos/test.mp4";

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    "/public/videos/de",
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            return $this->redirectToRoute("route_home");
        }

        return $this->render('admin/admin.html.twig', [
            'globals' => Util::get_globals(),
            'form' => $form->createView()
        ]);
    }
}