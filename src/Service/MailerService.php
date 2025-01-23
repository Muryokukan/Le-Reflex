<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    public function __construct(
        private UserRepository $userRepository,
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    public function sendContactNotification(ContactMessage $contactMessage): void
    {
        // TODO: Send only to users who want to receive contact notifications
        $users = $this->userRepository->findAll();
        $emailAddresses = array_map(fn($user) => $user->getEmail(), $users);
        $emailAddresses = implode(",", $emailAddresses);

        try {
            $email = (new TemplatedEmail())
                ->from(
                    new Address(
                        'noreply@' . $_ENV['APP_DOMAIN_NAME'],
                        $_ENV['APP_NAME']
                    )
                )
                ->to($emailAddresses)
                ->subject('Contact: ' . $contactMessage->getSubject())
                ->htmlTemplate('emails/contact_notification.html.twig')
                ->context([
                    'contact' => $contactMessage,
                ]);

            $this->mailer->send($email);
            $this->logger->info('Email de contact envoyé avec succès');
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de l\'email de contact: ' . $e->getMessage());
            throw $e;
        }
    }
}