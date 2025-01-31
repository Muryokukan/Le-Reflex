<?php

namespace App\Controller;

use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use App\Form\RoomReservationType;
use App\Service\CalendarEventBuilder;
use App\Service\RoomReservationValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation-salle')]
class RoomReservationController extends AbstractController
{
    #[Route(name: 'app_room_reservation_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        RoomReservationValidator $reservationValidator,
        CalendarEventBuilder $calendarEventBuilder
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

            try {
                $entityManager->persist($newReservation);
                $entityManager->flush();

                // TODO: Send notification/email

                $this->addFlash('success', 'Réservation créée avec succès');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de la réservation');
            }

            return $this->redirectToRoute('app_room_reservation_index');
        }

        return $this->render('room_reservation/index.html.twig', [
            'form' => $reservationForm->createView(),
            'calendarEvents' => json_encode($calendarEventBuilder->buildCalendarEvents()),
        ]);
    }
}