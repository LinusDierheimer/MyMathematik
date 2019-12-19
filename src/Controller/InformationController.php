<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InformationController extends AbstractController
{

    public function cookies()
    {
        return $this->render('site/information/cookies.html.twig');
    }

    private function contact_send_email(
        MailerInterface $mailer,
        $email_address,
        $text
    ) {
        $email = (new Email())
            ->from("noreply@mymathematik.com")
            ->to("Linus@Dierheimer.de")
            ->subject("MyMathematik contact email from: " . $email_address)
            ->text("Email from " . $email_address . ":\n\n" . $text)
        ;

        $sentEmail = $mailer->send($email);
    }

    private function contact_handle_post(
        Request $request,
        MailerInterface $mailer
    ) {
        if(
            !$request->request->has("email") ||
            !$request->request->has("g-recaptcha-response") ||
            !$request->request->has("text") 
        ) {
            return $this->redirectToRoute('route_information_contact', [
                "errors" => ["invalid_form"]
            ]);
        }

        $errors = [];
        $email = $request->request->get("email");
        $text = $request->request->get("text");
        $captcha = $request->request->get("g-recaptcha-response");

        if(strlen($email) > 4096)
            \array_push($errors, "too_long_email");
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            \array_push($errors, "invalid_email");

        $text_length = strlen($text);
        if($text_length < 1)
            \array_push($errors, "too_short_text");
        else if($text_length > 16384)
            \array_push($errors, "too_long_text");

        $captcha_verify = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($captcha_verify, CURLOPT_POST, 1);
        curl_setopt($captcha_verify, CURLOPT_POSTFIELDS, http_build_query([
            "secret" => $this->getParameter("captcha_secret"),
            "response" => $captcha
        ]));
        curl_setopt($captcha_verify, CURLOPT_RETURNTRANSFER, true);
        $captcha_verify_response = curl_exec($captcha_verify);
        curl_close($captcha_verify);
        if(!json_decode($captcha_verify_response)->success)
            \array_push($errors, "invalid_captcha");

        if(\count($errors) == 0)
        {
            $this->contact_send_email($mailer, $email, $text);
            return $this->redirectToRoute('route_information_contact');
        }
        
        return $this->render('site/information/contact.html.twig', [
            "errors" => $errors
        ]);
    }

    public function contact(
        Request $request,
        MailerInterface $mailer
    )
    {
        if($request->isMethod('post'))
            return $this->contact_handle_post($request, $mailer);

        return $this->render('site/information/contact.html.twig', [
            "errors" => []
        ]);
    }

    public function impressum()
    {
        return $this->render('site/information/impressum.html.twig');
    }

    public function sponsors()
    {
        return $this->render('site/information/sponsors.html.twig', [
            "sponsors" => Yaml::parseFile($this->getParameter('file_sponsors'))
        ]);
    }

    public function conditions()
    {
        return $this->render('site/information/conditions.html.twig');
    }

}