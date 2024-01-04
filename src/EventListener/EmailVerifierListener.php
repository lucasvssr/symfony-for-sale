<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\UserConfirmationEmailNotReceived;
use App\Event\UserRegistered;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailVerifierListener implements EventSubscriberInterface
{
    private EmailVerifier $mailer;

    public function __construct(EmailVerifier $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegistered::class => 'onUserRegistered',
            UserConfirmationEmailNotReceived::class => 'onUserConfirmationEmailNotReceived',
        ];
    }

    public function onUserRegistered(UserRegistered $event): void
    {
        // Code d'envoi du mail lors de l'inscription
        $user = $event->getUser();
        // code d'envoi du mail
        $this->mailer->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    public function onUserConfirmationEmailNotReceived(UserConfirmationEmailNotReceived $event): void
    {
        // Code d'envoi du mail de demande de validation de l'adresse mail
        $user = $event->getUser();
        // ... (code d'envoi du mail, remplacez-le par votre implémentation)
        $this->mailer->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
