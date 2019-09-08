<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AccountAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $userRepository;
    private $container;
    private $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ContainerInterface $container,
        RequestStack $requestStack
        
    ){
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    public function supports(Request $request)
    {
        return $request->isMethod("POST") && (
            $request->attributes->get("_route") === "route_account_login" ||
            $request->attributes->get("_route") === "route_account_register"
        );
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            "email" => $request->request->get("email_input"),
            "password" => $request->request->get("password_input"),
            "csrf_token" => $request->request->get("csrf_token_input"),
            "remember_me" => $request->request->get("remember_me_input"),
            "show_password" => $request->request->get("show_password_input")
        ];

        if($request->attributes->get("_route") === "route_account_register")
        {
            $credentials["password_repeat"] = $request->request->get("password_repeat_input");
            $credentials["g-recaptcha-response"] = $request->request->get("g-recaptcha-response");

            $request->getSession()->set("show_password", $credentials["show_password"]);
        }

        $request->getSession()->set(Security::LAST_USERNAME, $credentials["email"]);
        $request->getSession()->set("last_email", $credentials["email"]);
        $request->getSession()->set("last_remember_me", $credentials["remember_me"]);
        $request->getSession()->set("last_show_password", $credentials["show_password"]);

        return $credentials;
    }

    private function login_get_user($credentials, Request $request)
    {

        $errors = [];

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken("authenticate", $credentials["csrf_token"])))
            \array_push($errors, "invalid_csrf_token");
        
        if(strlen($credentials["password"]) < 6)
            \array_push($errors, "too_short_password");
        else if(strlen($credentials["password"]) > 4096)
            \array_push($errors, "too_long_password");

        if(\strlen($credentials["email"]) > 4096)
            \array_push($errors, "too_long_email");

        $user = $this->userRepository->findOneByEmail($credentials["email"]);
        
        if(!$user)
            \array_push($errors, "user_not_found");
        else if(!$this->passwordEncoder->isPasswordValid($user, $credentials["password"]))
            \array_push($errors, "wrong_password");

        if(\count($errors) > 0)
            throw new CustomUserMessageAuthenticationException("login_failed", $errors);

        return $user;
    }

    private function register_create_user($credentials, Request $request)
    {

        $errors = [];

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken("authenticate", $credentials["csrf_token"])))
            \array_push($errors, "invalid_csrf_token");

        if(strlen($credentials["email"]) > 4096)
            \array_push($errors, "too_long_email");
        else if(!filter_var($credentials["email"], FILTER_VALIDATE_EMAIL))
            \array_push($errors, "invalid_email");

        $password_length = strlen($credentials["password"]);
        if($password_length < 6)
            \array_push($errors, "too_short_password");
        else if($password_length > 4096)
            \array_push($errors, "too_long_password");

        if($credentials["show_password"] != "true")
            if($credentials["password_repeat"] != $credentials["password"])
                \array_push($errors, "unequal_passwords");  

        if($this->userRepository->findOneByEmail($credentials["email"]) != null)
            \array_push($errors, "already_used_email");

        $captcha_verify = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($captcha_verify, CURLOPT_POST, 1);
        curl_setopt($captcha_verify, CURLOPT_POSTFIELDS, http_build_query([
            "secret" => $this->container->getParameter("captcha_secret"),
            "response" => $credentials["g-recaptcha-response"]
        ]));
        curl_setopt($captcha_verify, CURLOPT_RETURNTRANSFER, true);
        $captcha_verify_response = curl_exec($captcha_verify);
        curl_close($captcha_verify);
        if(!json_decode($captcha_verify_response)->success)
            \array_push($errors, "invalid_captcha");

        if(\count($errors) > 0)
            throw new CustomUserMessageAuthenticationException("register_failed", $errors);

        $user = new User();
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $credentials["password"]
            )
        );
        $user->setEmail($credentials["email"]);
        $user->setRoles([]);
        $this->userRepository->registerUser($user);

        return $user;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $request = $this->requestStack->getCurrentRequest();
        $route = $request->attributes->get("_route");
        if($route === "route_account_login")
            return $this->login_get_user($credentials, $request);
        else if($route === "route_account_register")
            return $this->register_create_user($credentials, $request);
        else
            throw new \LogicException("Unsupported route");
        
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true; //Do Checks in getUser method
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            $request->getSession()->set("authentication_failed", true);
            $request->getSession()->set("errors", $exception->getMessageData());
        }

        return new RedirectResponse($this->urlGenerator->generate($request->attributes->get("_route")));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($request->hasSession()) {
            $request->getSession()->set("authentication_failed", false);
            $request->getSession()->set("errors", []);
        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate("route_account_me"));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate("route_account_login");
    }
}
