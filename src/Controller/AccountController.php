<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserDeletionManager;
use App\Service\VerificationEmailManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{

    public function me(
        Request $request
    ) {
        $user = $this->getUser();

        if($user == null)
        {
            $result =  $this->render('site/account/not_logged_in.html.twig', [
                "errors" => $request->getSession()->get("account_errors", []),
                "infos" => $request->getSession()->get("account_infos", [])
            ]);
    
            $request->getSession()->set("account_errors", []);
            $request->getSession()->set("account_infos", []);
    
            return $result;
        }

        return $this->redirectToRoute('route_account_user', [
            "id" => $user->getId()
        ]);
    }

    public function action(
        Request $request,
        VerificationEmailManager $verificationEmailManager
    ) {

        $user = $this->getUser();

        if($user == null)
        {
            return $this->json([
                "successfull" => false,
                "errorId" => 1,
                "error" => "Request from an not logged in user"
            ]);
        }

        if($request->query->has("show_name"))
        {
            $user->setShowName($request->query->get('show_name'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        if($request->query->has("verify_email"))
        { 
            $verificationEmailManager->send($user);
        }

        return $this->json([
            "successfull" => true
        ]);

    }

    public function verify(
        Request $request,
        VerificationEmailManager $verificationEmailManager
    ){
        if(
            !$request->query->has("id") ||
            !$request->query->has("secret")
        ) {
            $request->getSession()->set("account_errors", ["account.show.errors.invalid_query"]);
            return $this->redirectToRoute('route_account_me');
        }

        $res = $verificationEmailManager->verify($request->query->get("id"), $request->query->get("secret"));

        if(strpos($res, "infos") === false)
            $request->getSession()->set("account_errors", [$res]);
        else
            $request->getSession()->set("account_infos", [$res]);

        if($this->getUser() !== null)
        {
            return $this->redirectToRoute("route_account_user", [
                "id" => $this->getUser()->getId()
            ]);
        }
        else
        {
            return $this->redirectToRoute("route_account_me");
        }

    }

    public function delete(
        UserDeletionManager $userDeletionManager
    ) {
        $user = $this->getUser();

        if($user != null)
            $userDeletionManager->delete($user);

        return $this->redirectToRoute("route_home");
    }

    public function user(
        Request $request,
        UserRepository $userRepository,
        VerificationEmailManager $verificationEmailManager,
        $id
    ){

        $user = null;
        $thisUser = $this->getUser();
        if($thisUser != null && $thisUser->getId() == $id)
            $user = $thisUser;
        else
            $user = $userRepository->find($id);

        if($user == null)
        {
            return $this->render('site/account/user404.html.twig', [
                "id" => $id
            ]);
        }

        if($request->isMethod("post"))
        {

            if($thisUser == null)
                return $this->json([
                    "errorId" => 1,
                    "error" => "Request from an not logged in user",
                    "requestId" => $id,
                    "ownId" => -1
                ]);

            if($thisUser->getId() != $id)
                return $this->json([
                    "errorId" => 2,
                    "error" => "Request to an alien user",
                    "requestedId" => $id,
                    "ownId" => $thisUser->getId()
                ]);

            if($request->request->has("show_name"))
            {
                $thisUser->setShowName($request->request->get('show_name'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('route_account_user', [
                    "id" => $id
                ]);
            }

            if($request->request->has("sendVerifyEmail"))
            {
                $verificationEmailManager->send($user);
            }

        }

        $result =  $this->render('site/account/account.html.twig', [
            "user" => $user,
            "errors" => $request->getSession()->get("account_errors", []),
            "infos" => $request->getSession()->get("account_infos", [])
        ]);

        $request->getSession()->set("account_errors", []);
        $request->getSession()->set("account_infos", []);

        return $result;

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