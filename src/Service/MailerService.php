<?php

namespace App\Service;

use App\Entity\Affiliate;
use Swift_Mailer;

class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;

    /**
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Affiliate $affiliate
     */
    public function sendActivationEmail(Affiliate $affiliate): void
    {
        $message = (new \Swift_Message())
            ->setSubject('Account activation')
            ->setTo($affiliate->getEmail())
            ->setBody('Your account has been activated successfully! Your token is: ' . $affiliate->getToken());

        $this->mailer->send($message);
    }
}
