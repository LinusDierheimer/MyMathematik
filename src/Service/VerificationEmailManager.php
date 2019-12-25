<?php

namespace App\Service;

use App\Entity\PendingEmailVerification;
use App\Entity\User;
use App\Repository\PendingEmailVerificationRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class VerificationEmailManager
{

    protected $pendingEmailVerificationRepository;
    protected $userRepository;
    protected $mailer;
    protected $translator;
    protected $container;

    public function __construct(
        PendingEmailVerificationRepository $pendingEmailVerificationRepository,
        UserRepository $userRepository,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        ContainerInterface $container
    ) {
        $this->pendingEmailVerificationRepository = $pendingEmailVerificationRepository;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->container = $container;
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

    public function verify(
        $userId, $secret
    ) {

        $user = $this->userRepository->find($userId);

        if($user === null)
            return "account.show.errors.not_found";

        $pendingEmailVerification = $this->pendingEmailVerificationRepository->findOneByUserId($userId);
        
        if($pendingEmailVerification == null)
            return "account.show.errors.already_verified";

        if($pendingEmailVerification->getSecret() !== $secret)
            return "account.show.errors.wrong_secret";

        if(!$this->small_enough($pendingEmailVerification->getDate()))
            return "account.show.errors.out_of_date";

        $this->pendingEmailVerificationRepository->delete($pendingEmailVerification);

        $user->setEmailVerified(true);
        $this->userRepository->save($user);

        return "account.show.infos.successfull_verified";
    }

    public function send(User $user)
    {
        $user->setEmailVerified(false);
        $userId = $user->getId();
        $secret = md5(random_bytes(255));

        $pendingEmailVerification = $this->pendingEmailVerificationRepository->findOneByUserId($userId);

        if($pendingEmailVerification == null)
        {
            $pendingEmailVerification = new PendingEmailVerification();
            $pendingEmailVerification->setUserId($userId);
        }

        $pendingEmailVerification->setSecret($secret);
        $pendingEmailVerification->setDate(new DateTime());

        $this->pendingEmailVerificationRepository->save($pendingEmailVerification);

        $server = $this->container->getParameter('server_address');

        $email = (new TemplatedEmail())
            ->from("noreply@mymathematik.com")
            ->to($user->getEmail())
            ->subject($this->translator->trans("email.verify.subject"))
            ->htmlTemplate("email/verifyEmail.html.twig")
            ->context([
                "url" => "$server/account/verify?id=$userId&secret=$secret"
            ])
        ;

        $this->mailer->send($email);
    }

}