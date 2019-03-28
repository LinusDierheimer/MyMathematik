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

    public function me(Util $util)
    {
        return $this->render('site/account/account.html.twig', [
            'globals' => $util->get_globals()
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
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('site/account/register.html.twig', [
            'globals' => $util->get_globals(),
            'registrationForm' => $form->createView(),
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