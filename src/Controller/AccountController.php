<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AccountAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AccountController extends AbstractController
{

    public function redirect_me()
    {
        return $this->redirectToRoute('route_account_me');
    }

    public function redirect_login()
    {
        return $this->redirectToRoute('route_account_login');
    }

    public function redirect_register()
    {
        return $this->redirectToRoute('route_account_register');
    }

    public function redirect_logout()
    {
        return $this->redirectToRoute('route_account_logout');
    }

    public function me()
    {
        return $this->render('site/account/account.html.twig');
    }

    public function register(Request $request): Response
    {
        return $this->render("site/account/register.html.twig", [
            "errors" => $request->getSession()->get("errors", []),
            "last_email" => $request->getSession()->get("last_email"),
            "last_remember_me" => $request->getSession()->get("last_remember_me", true),
            "show_password" => $request->getSession()->get("last_show_password", false)
        ]);
    }

    public function login(Request $request): Response
    {
        return $this->render("site/account/login.html.twig", [
            "errors" => $request->getSession()->get("errors", []),
            "last_email" => $request->getSession()->get("last_email"),
            "last_remember_me" => $request->getSession()->get("last_remember_me", true),
            "show_password" => $request->getSession()->get("last_show_password", false)
        ]);
    }
}