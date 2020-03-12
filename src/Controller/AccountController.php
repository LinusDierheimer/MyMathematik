<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserDeletionManager;
use App\Service\UserPasswordResetManager;
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

    public function request_password_reset(
        Request $request,
        UserPasswordResetManager $userPasswordResetManager
    ) {

        $errors = [];

        if($request->isMethod("POST"))
        {
            if(!$request->request->has("email"))
            {
                array_push($errors, "invalid_request");
            }
            else
            {
                $email = $request->request->get("email");

                if(strlen($email) > 4096)
                    \array_push($errors, "too_long_email");
                else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    \array_push($errors, "invalid_email");
                else
                    if(!$userPasswordResetManager->request_reset_if_email_exist($email))
                        \array_push($errors, "No accout with this email");
            }

            if(count($errors) === 0)
            {
                return $this->redirectToRoute('route_account_login');
            }
        }

        return $this->render("site/account/request_password_reset.html.twig", [
            "last_email" => $request->getSession()->get("last_email"),
            "errors" => $errors
        ]);
    }

    public function perform_password_reset(
        Request $request,
        UserPasswordResetManager $userPasswordResetManager,
        UserRepository $userRepository
    ) {

        if(
            !$request->query->has("id") ||
            !$request->query->has("secret")
        ) {
            $request->getSession()->set("account_errors", ["account.show.errors.invalid_query"]);
            return $this->redirectToRoute('route_account_me');
        }

        $userId = $request->query->get("id");
        $secret = $request->query->get("secret");

        $res = $userPasswordResetManager->validateResetParams($userId, $secret);
        
        if($res["error"])
        {
            $request->getSession()->set("account_errors", [$res["message"]]);
            return $this->redirectToRoute("route_account_me");
        }            

        return $this->render("site/account/perform_password_reset.html.twig", [
            "user" => $userRepository->find($userId),
            "secret" => $secret,
            "show_password" => $request->getSession()->get("last_show_password", false)
        ]);
    
    }

    public function action_perform_password_reset(
        Request $request
    ) {
        if(!$request->isMethod("POST"))
        {
            $request->getSession->set("account_errors", ["actionperformpasswordreset must be called POST"]);
            return $this->redirectToRoute("route_account_me");
        }

        if(
            !$request->request->has("userId") ||
            !$request->request->has("secret") ||
            !$request->request->has("password")
        ) {
            
        }
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
        $res = $this->render("site/account/register.html.twig", [
            "errors" => $request->getSession()->get("errors", []),
            "last_email" => $request->getSession()->get("last_email"),
            "last_remember_me" => $request->getSession()->get("last_remember_me", true),
            "show_password" => $request->getSession()->get("last_show_password", false)
        ]);

        $request->getSession()->remove("errors");

        return $res;
    }

    public function login(Request $request): Response
    {
        $res = $this->render("site/account/login.html.twig", [
            "errors" => $request->getSession()->get("errors", []),
            "last_email" => $request->getSession()->get("last_email"),
            "last_remember_me" => $request->getSession()->get("last_remember_me", true),
            "show_password" => $request->getSession()->get("last_show_password", false)
        ]);

        $request->getSession()->remove("errors");

        return $res;
    }
}