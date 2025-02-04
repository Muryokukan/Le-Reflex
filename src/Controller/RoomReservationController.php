<?php

namespace App\Controller;

use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use App\Form\RoomReservationType;
use App\Service\CalendarEventBuilder;
use App\Service\MailerService;
use App\Service\RoomReservationValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomReservationController extends AbstractController
{
    #[Route('/reservation-salle', name: 'app_room_reservation_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        RoomReservationValidator $reservationValidator,
        CalendarEventBuilder $calendarEventBuilder,
        MailerService $mailer,
    ): Response {
        $newReservation = new RoomReservation();
        $reservationForm = $this->createForm(RoomReservationType::class, $newReservation);
        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            $validationResult = $reservationValidator->validateReservationDate($newReservation);

            if (!$validationResult['isValid']) {
                $this->addFlash('error', $validationResult['message']);
                return $this->redirectToRoute('app_room_reservation_index');
            }

            $newReservation->setStatus(RoomReservationStatus::WAITING_DATE_CONFIRMATION);

            $entityManager->persist($newReservation);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Vous pouvez dès à présent venir payer sur place, ou effectuer le virement et nous envoyer la preuve par email à l\'adresse : lereflex@hotmail.com .'
            );

            try {
                $mailer->sendReservationNotification($newReservation);
                $this->addFlash(
                    'success',
                    'Demande de réservation envoyée avec succès.'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'warning',
                    'Votre demande de réservation a bien été enregistré mais nous rencontrons des difficultés techniques pour envoyer la notification. Notre traiterons votre demande dès que possible et restons contactable directement par email ou téléphone.'
                );
            }

            return $this->redirectToRoute('app_room_reservation_index');
        }

        return $this->render('room_reservation/index.html.twig', [
            'form' => $reservationForm->createView(),
            'calendarEvents' => json_encode($calendarEventBuilder->buildCalendarEvents()),
        ]);
    }
}