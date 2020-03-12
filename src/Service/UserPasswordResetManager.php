<?php

namespace App\Service;

use App\Entity\PendingPasswordReset;
use App\Entity\User;
use App\Repository\PendingPasswordResetRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserPasswordResetManager
{

    protected $userRepository;
    protected $pendingPasswordResetRepository;
    protected $container;
    protected $mailer;
    protected $translator;

    public function __construct(
        UserRepository $userRepository,
        PendingPasswordResetRepository $pendingPasswordResetRepository,
        MailerInterface $mailer,
        ContainerInterface $container,
        TranslatorInterface $translator
    ) {
        $this->userRepository = $userRepository;
        $this->pendingPasswordResetRepository = $pendingPasswordResetRepository;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->translator = $translator;
    }

    public function request_reset(
        User $user
    ) {
        $userId = $user->getId();
        $secret = md5(random_bytes(255));

        $pendingPasswordReset = $this->pendingPasswordResetRepository->findOneByUserId($userId);

        if($pendingPasswordReset === null)
        {
            $pendingPasswordReset = new PendingPasswordReset();
            $pendingPasswordReset->setUserId($userId);
        }

        $pendingPasswordReset->setSecret($secret);
        $pendingPasswordReset->setDate(new DateTime());

        $this->pendingPasswordResetRepository->save($pendingPasswordReset);

        $server = $this->container->getParameter("server_address");

        $email = (new TemplatedEmail())
            ->from("noreply@mymathematik.com")
            ->to($user->getEmail())
            ->subject($this->translator->trans("email.PasswordReset.subject"))
            ->htmlTemplate("email/passwordResetEmail.html.twig")
            ->context([
                "url" => "$server/account/performpasswordreset?id=$userId&secret=$secret"
            ])
        ;

        $this->mailer->send($email);
    }

    public function request_reset_if_email_exist(
        $email
    ) {
        $user = $this->userRepository->findOneByEmail($email);

        if($user !== null)
        {
            $this->request_reset($user);
            return true;
        }

        return false;
    }

    private function small_enough(DateTime $dateTime)
    {   
        $now = new DateTime();
        $diff = $dateTime->diff($now);

        return
            $diff->y == 0 &&
            $diff->m == 0 &&
            $diff->d == 0 &&
            $diff->h < 12
        ;
    }

    public function validateResetParams($userId, $secret)
    {
        $user = $this->userRepository->find($userId);

        if($user == null)
            return [
                "error" => true,
                "message" => "User doesn't exist"
            ];

        $pendingPasswordReset = $this->pendingPasswordResetRepository->findOneByUserId($userId);

        if($pendingPasswordReset == null)
            return [
                "error" => true,
                "message" => "Es existiert keine Änderungsanfrage"
            ];

        if($pendingPasswordReset->getSecret() !== $secret)
            return [
                "error" => true,
                "message" => "Secrets stimmen nicht überein"
            ];

        if(!$this->small_enough($pendingPasswordReset->getDate()))
            return [
                "error" => true,
                "message" => "Abgelaufen"
            ];

        return [
            "error" => false
        ];
    }

    public function resetPassword($userId, $secret)
    {
        $res = $this->validateResetParams($userId, $secret);

        if($res["error"])
            return $res;

        
        $user = $this->userRepository->find($userId);
        $pendingPasswordReset = $this->pendingPasswordResetRepository->findOneByUserId($userId);

    }

}