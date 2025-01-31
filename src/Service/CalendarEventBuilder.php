<?php

namespace App\Service;

use App\Entity\ClosurePeriod;
use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use App\Repository\ClosurePeriodRepository;
use App\Repository\RoomReservationRepository;

class CalendarEventBuilder
{
    private const CONFIRMED_RESERVATION_COLOR = '#ff0000';
    private const PENDING_RESERVATION_COLOR = '#ffa500';
    private const CLOSURE_COLOR = '#000000';

    public function __construct(
        private RoomReservationRepository $reservationRepository,
        private ClosurePeriodRepository $closurePeriodRepository
    ) {
    }

    public function buildCalendarEvents(): array
    {
        return array_merge(
            $this->buildConfirmedReservationEvents(),
            $this->buildPendingReservationEvents(),
            $this->buildClosurePeriodEvents()
        );
    }

    private function buildConfirmedReservationEvents(): array
    {
        $confirmedReservations = $this->reservationRepository->findBy([
            'status' => RoomReservationStatus::CONFIRMED
        ]);

        return $this->formatReservationEvents($confirmedReservations, self::CONFIRMED_RESERVATION_COLOR);
    }

    private function buildPendingReservationEvents(): array
    {
        $pendingReservations = $this->reservationRepository->findBy([
            'status' => [
                RoomReservationStatus::WAITING_PAYMENT_CONFIRMATION
            ]
        ]);

        return $this->formatReservationEvents($pendingReservations, self::PENDING_RESERVATION_COLOR);
    }

    private function formatReservationEvents(array $reservations, string $color): array
    {
        return array_map(function (RoomReservation $reservation) use ($color) {
            return [
                'start' => $reservation->getReservationDate()->format('Y-m-d'),
                'end' => $reservation->getReservationDate()->modify('+1 day')->format('Y-m-d'),
                'display' => 'background',
                'color' => $color,
                'type' => 'reservation',
                'status' => $reservation->getStatus()->value
            ];
        }, $reservations);
    }

    private function buildClosurePeriodEvents(): array
    {
        $closurePeriods = $this->closurePeriodRepository->findAll();

        return array_map(function (ClosurePeriod $closurePeriod) {
            return [
                'start' => $closurePeriod->getStartDate()->format('Y-m-d'),
                'end' => $closurePeriod->getEndDate()->modify('+1 day')->format('Y-m-d'),
                'display' => 'background',
                'color' => self::CLOSURE_COLOR,
                'title' => $closurePeriod->getReason() ?: '',
                'type' => 'closure',
            ];
        }, $closurePeriods);
    }
}
