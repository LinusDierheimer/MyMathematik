<?php

namespace App\Controller;

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
    public function index()
    {
        return $this->render('site/admin/admin.html.twig');
    }

    public function videoconfig()
    {
        throw \RuntimeException("Not implemented");
    }

    public function languageconfig()
    {
        throw \RuntimeException("Not implemented");
    }

    public function doaction()
    {
        throw \RuntimeException("Not implemented");
    }

}