<?php

namespace App\Http\Responses;

use App\Exceptions\DataInvalidException;
use App\Models\Reservation;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class ReservationFormResponseReservation extends Data
{
    public function __construct(
        public int $id,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $check_in_date,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $check_out_date,
        public int $hotel_id,
        public int $room_type_id,
        public int $room_id,
        public string $guest_name,
        public string $guest_email,
        public string $remarks,
    ) {
    }

    public static function fromModel(Reservation $reservation): self
    {
        if ($reservation->room === null ||
            $reservation->room->roomType === null
        ) {
            throw new DataInvalidException();
        }

        return new self(
            id: $reservation->id,
            check_in_date: $reservation->check_in_date,
            check_out_date: $reservation->check_out_date,
            hotel_id: $reservation->room->roomType->hotel_id,
            room_type_id: $reservation->room->room_type_id,
            room_id: $reservation->room_id,
            guest_name: $reservation->guest_name,
            guest_email: $reservation->guest_email,
            remarks: $reservation->remarks,
        );
    }
}
