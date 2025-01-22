<?php

namespace App\Enum;

enum RoomReservationStatus: string
{
    case WAITING_DATE_CONFIRMATION = 'waiting_date_confirmation';
    case WAITING_PAYMENT = 'waiting_payment';
    case CONFIRMED = 'confirmed';
    case REFUSED = 'refused';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::WAITING_DATE_CONFIRMATION => 'En attente de validation des dates',
            self::WAITING_PAYMENT => 'En attente du paiment',
            self::CONFIRMED => 'Confirmée',
            self::REFUSED => 'Refusée',
            self::CANCELLED => 'Annulée',
        };
    }
}
