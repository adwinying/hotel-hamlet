<?php

namespace App\Actions\Room;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;

class GetAvailableRooms
{
    public function __construct(protected FilterRoom $filterRoom)
    {
    }

    /**
     * Get available rooms
     *
     * @param RoomType    $roomType     Room Type
     * @param string      $checkInDate  Check In Date (Y-m-d)
     * @param string      $checkOutDate Check Out Date (Y-m-d)
     * @param Reservation $reservation  Existing reservation
     */
    public function execute(
        RoomType $roomType,
        string $checkInDate,
        string $checkOutDate,
        Reservation $reservation = null
    ): Collection {
        return $this->filterRoom->execute(Room::query(), [
            'room_type_id' => $roomType->id,
        ])->where(
            fn ($q) => $q
                ->whereDoesntHave('reservations', fn ($q) => $q
                    ->where('check_in_date', '<', $checkOutDate)
                    ->where('check_out_date', '>', $checkInDate))
                ->orWhereHas('reservations', fn ($q) => $q
                    ->where('id', $reservation->id ?? null))
        )->get();
    }
}
