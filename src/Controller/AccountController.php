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

    public function me()
    {
        return $this->render('account/account.html.twig', [
            'classes' => Util::get_classes(),
            'informations' => Util::get_informations(),
            'sponsors' => Util::get_sponsors()
        ]);
    }

    public function register(
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

        return $this->render('account/register.html.twig', [
            'classes' => Util::get_classes(),
            'informations' => Util::get_informations(),
            'sponsors' => Util::get_sponsors(),
            'registrationForm' => $form->createView(),
        ]);
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'classes' => Util::get_classes(),
            'informations' => Util::get_informations(),
            'sponsors' => Util::get_sponsors(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}