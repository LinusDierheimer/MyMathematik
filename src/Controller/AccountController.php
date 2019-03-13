<?php

namespace App\Controller;

use App\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{

    public function redirectToMe()
    {
        return $this->redirectToRoute('route_account_me', []);
    }

    public function me()
    {
        return $this->render('account.html.twig', [
            'classes' => Util::get_classes(),
            'informations' => Util::get_informations(),
            'sponsors' => Util::get_sponsors()
        ]);
    }
}