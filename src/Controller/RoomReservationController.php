<?php

namespace App\Controller;

use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use App\Form\RoomReservationType;
use App\Repository\RoomReservationRepository;
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
        RoomReservationRepository $reservationRepository
    ): Response {
        $reservation = new RoomReservation();
        $form = $this->createForm(RoomReservationType::class, $reservation);

        $existingReservations = $reservationRepository->findBy(['status' => RoomReservationStatus::CONFIRMED]);
        $reservations = array_map(function ($res) {
            return [
                'start' => $res->getReservationDate()->format('Y-m-d'),
                'end' => $res->getReservationDate()->modify('+1 day')->format('Y-m-d'), // FullCalendar needs an end_date
                'display' => 'background',
                'color' => '#ff0000'
            ];
        }, $existingReservations);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingReservation = $reservationRepository->findOneBy([
                'reservationDate' => $reservation->getReservationDate(),
                'status' => RoomReservationStatus::CONFIRMED
            ]);

            if ($existingReservation) {
                $this->addFlash('error', 'Cette date est déjà réservée');
                return $this->redirectToRoute('app_room_reservation_index');
            }

            $reservation->setStatus(RoomReservationStatus::WAITING_DATE_CONFIRMATION);

            try {
                $entityManager->persist($reservation);
                $entityManager->flush();

                // TODO: Send notification/email

                $this->addFlash('success', 'Réservation créée avec succès');
                
                return $this->redirectToRoute('app_room_reservation_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de la réservation');
            }
        }

        return $this->render('room_reservation/index.html.twig', [
            'form' => $form->createView(),
            'reservations' => json_encode($reservations),
        ]);
    }
}