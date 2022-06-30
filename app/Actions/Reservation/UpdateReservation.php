<?php

namespace App\Actions\Reservation;

use App\Actions\Room\GetAvailableRooms;
use App\Exceptions\RoomUnavailableException;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Arr;

class UpdateReservation
{
    public function __construct(
        protected GetAvailableRooms $getAvailableRooms,
    ) {
    }

    /**
     * Update reservation
     *
     * @param Reservation          $reservation Reservation to be updated
     * @param array<string, mixed> $input       Data to update
     */
    public function execute(Reservation $reservation, array $input = []): Reservation
    {
        $newRoomId = $input['room_id'] ?? $reservation->room_id;
        /** @var string */
        $newCheckInDate = $input['check_in_date'] ?? $reservation->check_in_date;
        /** @var string */
        $newCheckOutDate = $input['check_out_date'] ?? $reservation->check_out_date;

        // if check_in_date, check_out_date or room_id is changed,
        // recheck room availability
        if (Arr::hasAny($input, ['check_in_date', 'check_out_date', 'room_id'])
            && ($reservation->room_id != $newRoomId
                || $reservation->check_in_date != $newCheckInDate
                || $reservation->check_out_date != $newCheckOutDate)) {
            /** @var Room */
            $room = Room::findOrFail($newRoomId);
            /** @var RoomType */
            $roomType = $room->roomType;

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
