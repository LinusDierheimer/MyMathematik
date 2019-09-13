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
    public function me()
    {
        return $this->render('site/account/account.html.twig', [
            "user" => $this->getUser()
        ]);
    }

    public function user(
        UserRepository $userRepository,
        $id
    ){

        $user = $userRepository->find($id);

        if($user == null)
        {
            return $this->render('site/account/user404.html.twig', [
                "raw_email" => $email,
                "email" => $email
            ]);
        }

        return $this->render('site/account/account.html.twig', [
            "user" => $user
        ]);
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