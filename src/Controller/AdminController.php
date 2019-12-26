<?php

namespace App\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->render('site/admin/admin.html.twig');
    }

    public function videoconfig()
    {
        throw new RuntimeException("Not implemented");
    }

    public function languageconfig()
    {
        throw new RuntimeException("Not implemented");
    }

    public function doaction()
    {
        throw new RuntimeException("Not implemented");
    }

}