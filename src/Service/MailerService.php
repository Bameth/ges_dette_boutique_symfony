<?php
namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(string $to, string $name, string $login, string $password): void
    {
        $email = (new Email())
            ->from('amethba8826@gmail.com')
            ->to($to)
            ->subject('Confirmation de création de compte')
            ->html('<p>Bonjour ' . $name . ',</p>
                    <p>Votre compte a été créé avec succès.</p>
                    <p><strong>Login:</strong> ' . $login . '</p>
                    <p><strong>Mot de passe:</strong> ' . $password . '</p>
                    <p>Cordialement,<br>L\'équipe de votre site.</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Gérer l'exception si nécessaire
        }
    }
}
