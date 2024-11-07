<?php

namespace App\Mailer;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class WelcomeMailer
{
    private $mailer;
    private $session;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendWelcomeEmail(string $toEmail)
    {
        $email = (new Email())
            ->from('ametba8826@gmail.com')
            ->to($toEmail)
            ->subject('Bienvenue sur notre site !')
            ->text('Merci de vous être inscrit. Votre compte a été créé avec succès.')
            ->html('<p>Merci de vous être inscrit. Votre compte a été créé avec succès.</p>');

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Ajout de log pour faciliter le débogage
            $this->session->getFlashBag()->add('error', 'Erreur lors de l\'envoi de l\'email de bienvenue.');
        }
    }
}
