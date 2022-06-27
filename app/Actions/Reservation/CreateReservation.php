<?php

namespace App\Actions\Reservation;

use App\Actions\Room\GetAvailableRooms;
use App\Exceptions\RoomUnavailableException;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CreateReservation
{
    public function __construct(
        protected GetAvailableRooms $getAvailableRooms,
    ) {
    }

    /**
     * Create reservation
     *
     * @param array<string, mixed> $input Data to update
     */
    public function execute(array $input = []): Reservation
    {
        // if room_type_id is provided select the first available room
        if ($roomTypeId = Arr::pull($input, 'room_type_id')) {
            /** @var RoomType */
            $roomType = RoomType::findOrFail($roomTypeId);

            $rooms = $this->getAvailableRooms->execute(
                $roomType,
                $input['check_in_date'],
                $input['check_out_date'],
            );

            if ($rooms->isEmpty()) {
                throw new RoomUnavailableException();
            }

            $input['room_id'] = $rooms->first()?->id;

        // else assume room_id is set and check whether room is available
        } else {
            /** @var Room */
            $room = Room::findOrFail($input['room_id']);
            /** @var RoomType */
            $roomType = $room->roomType;

            $rooms = $this->getAvailableRooms->execute(
                $roomType,
                $input['check_in_date'],
                $input['check_out_date'],
            );

            if ($rooms->find($input['room_id']) === null) {
                throw new RoomUnavailableException();
            }
        }

        // Generate PIN
        $pin = $input['pin']
            ?? str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $hashedPin    = Hash::make($pin);
        $input['pin'] = $hashedPin;

        $reservation = Reservation::create($input);

        // restore PIN to unhashed value
        $reservation->pin = $pin;

        return $reservation;
    }
}
