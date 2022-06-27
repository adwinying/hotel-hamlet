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
    public function __construct(protected GetAvailableRooms $getAvailableRooms)
    {
    }

    /**
     * Create reservation
     *
     * @param array $params Data to create
     * @return App\Models\Reservation
     */
    public function execute(array $params = []): Reservation
    {
        // if room_type_id is provided select the first available room
        if ($roomTypeId = Arr::pull($params, 'room_type_id')) {
            $rooms = $this->getAvailableRooms->execute(
                RoomType::findOrFail($roomTypeId),
                $params['check_in_date'],
                $params['check_out_date'],
            );

            if ($rooms->empty()) {
                throw new RoomUnavailableException();
            }

            $params['room_id'] = $rooms->first()->id;

        // else assume room_id is set and check whether room is available
        } else {
            $roomType = Room::findOrFail($params['room_id'])->roomType;

            $rooms = $this->getAvailableRooms->execute(
                $roomType,
                $params['check_in_date'],
                $params['check_out_date'],
            );

            if ($rooms->find($params['room_id']) === null) {
                throw new RoomUnavailableException();
            }
        }

        // Generate PIN
        $pin = $params['pin']
            ?? str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $hashedPin     = Hash::make($pin);
        $params['pin'] = $hashedPin;

        $reservation = Reservation::create($params);

        // restore PIN to unhashed value
        $reservation->pin = $pin;

        return $reservation;
    }
}
