<?php

namespace App\Controller;

use App\Util;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AccountAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
        return $this->redirectToRoute('route_account_me');
    }

    public function redirect_logout()
    {
        return $this->redirectToRoute('route_account_logout');
    }

    public function me(Request $request, Util $util)
    {
        return $this->render('site/account/account.html.twig', [
            'globals' => $util->get_globals(),
            'debug' => $request->getSession()->get('debug', "default")
        ]);
    }

    public function register(
        Util $util,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        AccountAuthenticator $authenticator
    ): Response
    {

        if($request->request->count() > 0)
        {
            $errors = [];
            if(!$request->request->has("email_input"))
                \array_push($errors, "no_email");
            if(!$request->request->has("password_input"))
                \array_push($errors, "no_password");
            if(!$request->request->has("g-recaptcha-response"))
                \array_push($errors, "no_captcha");
            else{
                $captcha_verify = curl_init("https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($captcha_verify, CURLOPT_POST, 1);
                curl_setopt($captcha_verify, CURLOPT_POSTFIELDS, http_build_query([
                    "secret" => $util->get_parameter("captcha_secret"),
                    "response" => $request->request->get("g-recaptcha-response")
                ]));
                curl_setopt($captcha_verify, CURLOPT_RETURNTRANSFER, true);
                $captcha_verify_response = curl_exec($captcha_verify);
                curl_close($captcha_verify);
    
                if(!json_decode($captcha_verify_response)->success)
                    \array_push($errors, "invalid_captcha");
            }

            $password = $request->request->get("password_input");
            $password_length = strlen($password);
            if($password_length < 6)
                array_push($errors, "too_short_password");
            else if($password_length > 4096)
                array_push($errors, "too_long_password");

            $email = $request->request->get('email_input');
            if(strlen($email) > 4096)
                array_push($errors, "too_long_email");
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                array_push($errors, "invalid_email");

            if(\count($errors) > 0)
                return $this->render("site/account/register.html.twig", [
                    "globals" => $util->get_globals(),
                    "errors" => $errors
                ]);

            $user = new User();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );
            $user->setEmail($email);
            $user->setRoles([]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('site/account/register.html.twig', [
            'globals' => $util->get_globals()
        ]);
    }

    public function login(Util $util, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('site/account/login.html.twig', [
            'globals' => $util->get_globals(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}