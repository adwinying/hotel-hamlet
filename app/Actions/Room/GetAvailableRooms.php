<?php

namespace App\Actions\Room;

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
     * @param RoomType $roomType     Room Type
     * @param string   $checkInDate  Check In Date (Y-m-d)
     * @param string   $checkOutDate Check Out Date (Y-m-d)
     */
    public function execute(
        RoomType $roomType,
        string $checkInDate,
        string $checkOutDate
    ): Collection {
        return $this->filterRoom->execute(Room::query(), [
            'room_type_id' => $roomType->id,
        ])->whereDoesntHave('reservations', fn ($q) => $q
            ->where('check_in_date', '<', $checkOutDate)
            ->where('check_out_date', '>', $checkInDate))->get();
    }
}
