<?php

namespace App\Actions\Reservation;

use App\Models\Reservation;

class DeleteReservation
{
    /**
     * Delete reservation
     *
     * @param Reservation $reservation Reservation to be deleted
     */
    public function execute(Reservation $reservation): bool
    {
        return $reservation->delete();
    }
}
