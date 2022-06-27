<?php

namespace App\Actions\Reservation;

use App\Actions\Room\GetAvailableRooms;
use App\Exceptions\RoomUnavailableException;
use App\Models\Reservation;
use App\Models\Room;
use Arr;

class UpdateReservation
{
    public function __construct(protected GetAvailableRooms $getAvailableRooms)
    {
    }

    /**
     * Update reservation
     *
     * @param Reservation $reservation Reservation to be updated
     * @param array       $params      Data to update
     * @return App\Models\Reservation
     */
    public function execute(Reservation $reservation, array $input = []): Reservation
    {
        $newRoomId       = $input['room_id']        ?? $reservation->room_id;
        $newCheckInDate  = $input['check_in_date']  ?? $reservation->check_in_date;
        $newCheckOutDate = $input['check_out_date'] ?? $reservation->check_out_date;

        // if check_in_date, check_out_date or room_id is changed,
        // recheck room availability
        if (Arr::hasAny($input, ['check_in_date', 'check_out_date', 'room_id'])
            && ($reservation->room_id != $newRoomId
                || $reservation->check_in_date != $newCheckInDate
                || $reservation->check_out_date != $newCheckOutDate)) {
            $roomType = Room::findOrFail($newRoomId)->roomType;

            $rooms = $this->getAvailableRooms->execute(
                $roomType,
                $newCheckInDate,
                $newCheckOutDate,
                $reservation,
            );

            if ($rooms->find($newRoomId) === null) {
                throw new RoomUnavailableException();
            }
        }

        $reservation->update($input);

        return $reservation;
    }
}
