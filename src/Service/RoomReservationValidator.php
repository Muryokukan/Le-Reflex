<?php

namespace App\Service;

use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use App\Repository\ClosurePeriodRepository;
use App\Repository\RoomReservationRepository;

class RoomReservationValidator
{
    public function __construct(
        private ClosurePeriodRepository $closurePeriodRepository,
        private RoomReservationRepository $reservationRepository
    ) {
    }

    public function validateReservationDate(RoomReservation $reservation): array
    {
        $normalizedDate = $this->normalizeDate($reservation->getReservationDate());

        $isUnavailable = $this->isDateInClosurePeriod($normalizedDate) ||
            $this->isDateUnavailable($normalizedDate);

        return [
            'isValid' => !$isUnavailable,
            'message' => $isUnavailable ? 'La date est indisponible' : ''
        ];
    }

    private function normalizeDate(\DateTimeImmutable $date): \DateTimeImmutable
    {
        return (clone $date)->setTime(0, 0, 0);
    }

    private function isDateInClosurePeriod(\DateTimeImmutable $date): bool
    {
        $closurePeriods = $this->closurePeriodRepository->findAll();

        foreach ($closurePeriods as $closurePeriod) {
            if (
                $date >= $closurePeriod->getStartDate() &&
                $date <= $closurePeriod->getEndDate()
            ) {
                return true;
            }
        }

        return false;
    }

    private function isDateUnavailable(\DateTimeImmutable $date): bool
    {
        $unavailableStatuses = [
            RoomReservationStatus::CONFIRMED,
            RoomReservationStatus::WAITING_PAYMENT_CONFIRMATION
        ];

        return $this->reservationRepository->findOneBy([
            'reservationDate' => $date,
            'status' => $unavailableStatuses
        ]) !== null;
    }
}
