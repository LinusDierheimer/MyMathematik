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
    public function me(Request $request)
    {
        $user = $this->getUser();

        if($user == null)
        {
            return $this->render('site/account/not_logged_in.html.twig');
        }

        return $this->redirectToRoute('route_account_user', [
            "id" => $user->getId()
        ]);
    }

    public function user(
        Request $request,
        UserRepository $userRepository,
        $id
    ){

        $user = null;
        $thisUser = $this->getUser();
        if($thisUser != null && $thisUser->getId() == $id)
            $user = $thisUser;
        else
            $user = $userRepository->find($id);

        if(
            $request->isMethod('post') &&
            $request->request->has('show_name')
        ){
            $user->setShowName($request->request->get('show_name'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('route_account_user', [
                "id" => $id
            ]);
        }

        if($user == null)
        {
            return $this->render('site/account/user404.html.twig', [
                "id" => $id
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