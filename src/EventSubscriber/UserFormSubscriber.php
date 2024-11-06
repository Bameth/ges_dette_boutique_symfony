<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class UserFormSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $event)
    {
        $user = $event->getData();

        // Check if the user is created and has a valid email
        if ($user && $user->getEmail()) {
            $this->sendConfirmationEmail($user);
        }
    }

    private function sendConfirmationEmail($user)
    {
        $email = (new Email())
            ->from('no-reply@votresite.com')
            ->to($user->getEmail())
            ->subject('Confirmation de la création de votre compte')
            ->html(
                "<p>Bonjour {$user->getNom()} {$user->getPrenom()},</p>
                <p>Votre compte a été créé avec succès.</p>
                <p>Voici les informations que vous avez saisies :</p>
                <ul>
                    <li><strong>Nom :</strong> {$user->getNom()}</li>
                    <li><strong>Prénom :</strong> {$user->getPrenom()}</li>
                    <li><strong>Login :</strong> {$user->getLogin()}</li>
                    <li><strong>Email :</strong> {$user->getEmail()}</li>
                </ul>"
            );

        $this->mailer->send($email);
    }
}
