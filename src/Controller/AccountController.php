<?php

namespace App\Controller;

use App\Util;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AccountAuthenticator;
use App\Repository\UserRepository;
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
        return $this->redirectToRoute('route_account_register');
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
        AccountAuthenticator $authenticator,
        UserRepository $userRepository
    ): Response
    {

        if($request->isMethod("POST") && $request->request->count() > 0)
        {
            $errors = [];
            if(!$request->request->has("email_input") ||
               !$request->request->has("password_input") ||
               !$request->request->has("password_repeat_input") ||
               !$request->request->has("g-recaptcha-response") ||
               !$request->request->has("single_password_field")
            ){
                \array_push($errors, "invalid_form");
            }else{

                //Verify Email
                $email = $request->request->get('email_input');
                if(strlen($email) > 4096)
                    array_push($errors, "too_long_email");
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    array_push($errors, "invalid_email");
                if($userRepository->findOneByEmail($email) != null)
                    array_push($errors, "already_used_email");

                //Verify Password
                $password = $request->request->get("password_input");
                $password_length = strlen($password);
                if($password_length < 6)
                    array_push($errors, "too_short_password");
                else if($password_length > 4096)
                    array_push($errors, "too_long_password");

                $single_password_field = !($request->request->get("single_password_field") != "true");

                if(!$single_password_field)
                    if($request->request->get("password_repeat_input") != $password)
                        array_push($errors, "unequal_passwords");

                //Verify Captcha
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

            if(\count($errors) > 0)
                return $this->render("site/account/register.html.twig", [
                    "globals" => $util->get_globals(),
                    "errors" => $errors,
                    "single_password_field" => $single_password_field,
                    "last_email" => $email 
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
            'globals' => $util->get_globals(),
            "errors" => []
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