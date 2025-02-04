<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\RoomReservation;
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
        $users = $this->userRepository->findBy(['contactNotification' => true]);

        foreach ($users as $user) {
            $email = (new TemplatedEmail())
                ->from(
                    new Address(
                        'noreply@' . $_ENV['APP_DOMAIN_NAME'],
                        $_ENV['APP_NAME']
                    )
                )
                ->to(new Address($user->getEmail()))
                ->subject('Contact: ' . $contactMessage->getSubject())
                ->htmlTemplate('emails/contact_notification.html.twig')
                ->textTemplate('emails/contact_notification.text.twig')
                ->context([
                    'contact' => $contactMessage,
                ]);

            try {
                $this->mailer->send($email);
                $this->logger->info('Email envoyé avec succès', [
                    'to' => $user->getEmail(),
                    'subject' => $contactMessage->getSubject(),
                    'type' => 'contact_notification',
                    'from' => 'noreply@' . $_ENV['APP_DOMAIN_NAME']
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur d\'envoi d\'email', [
                    'error' => $e->getMessage(),
                    'to' => $user->getEmail(),
                    'type' => 'contact_notification',
                    'subject' => $contactMessage->getSubject()
                ]);
                throw $e;
            }
        }
    }

    public function sendReservationNotification(RoomReservation $roomReservation): void
    {
        $users = $this->userRepository->findBy(['reservationNotification' => true]);

        foreach ($users as $user) {
            $email = (new TemplatedEmail())
                ->from(
                    new Address(
                        'noreply@' . $_ENV['APP_DOMAIN_NAME'],
                        $_ENV['APP_NAME']
                    )
                )
                ->to(new Address($user->getEmail()))
                ->subject('Nouvelle demande de réservation: ' . $roomReservation->getReservationDate()->format('d/m/Y') )
                ->htmlTemplate('emails/reservation_notification.html.twig')
                ->textTemplate('emails/reservation_notification.text.twig')
                ->context([
                    'reservation' => $roomReservation,
                ]);

            try {
                $this->mailer->send($email);
                $this->logger->info('Email envoyé avec succès', [
                    'to' => $user->getEmail(),
                    'subject' => 'Nouvelle demande de réservation: ' . $roomReservation->getReservationDate()->format('d/m/Y'),
                    'type' => 'reservation_notification',
                    'from' => 'noreply@' . $_ENV['APP_DOMAIN_NAME']
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur d\'envoi d\'email', [
                    'error' => $e->getMessage(),
                    'to' => $user->getEmail(),
                    'type' => 'reservation_notification',
                    'subject' => 'Nouvelle demande de réservation: ' . $roomReservation->getReservationDate()->format('d/m/Y')
                ]);
                throw $e;
            }
        }
    }
}