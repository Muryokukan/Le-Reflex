<?php

namespace App\Enum;

enum RoomReservationStatus: string
{
    case WAITING_DATE_CONFIRMATION = 'waiting_date_confirmation';
    case WAITING_PAYMENT_CONFIRMATION = 'waiting_payment_confirmation';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::WAITING_DATE_CONFIRMATION => 'En attente de validation des dates',
            self::WAITING_PAYMENT_CONFIRMATION => 'En attente de validation du paiment',
            self::CONFIRMED => 'Confirmée',
            self::CANCELLED => 'Annulée',
        };
    }
}
