<?php

namespace App\Http\Responses\Admin;

use App\Exceptions\DataInvalidException;
use App\Models\Reservation;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class ReservationIndexResponseRow extends Data
{
    public function __construct(
        public int $id,
        public int $room_id,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $check_in_date,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $check_out_date,
        public string $guest_name,
        public string $guest_email,
        public string $room_no,
        public string $room_type_name,
        public string $hotel_name,
    ) {
    }

    public static function fromModel(Reservation $reservation): self
    {
        if ($reservation->room === null ||
            $reservation->room->roomType === null ||
            $reservation->room->roomType->hotel === null
        ) {
            throw new DataInvalidException();
        }

        return new self(
            id: $reservation->id,
            room_id: $reservation->room_id,
            check_in_date: $reservation->check_in_date,
            check_out_date: $reservation->check_out_date,
            guest_name: $reservation->guest_name,
            guest_email: $reservation->guest_email,
            room_no: $reservation->room->room_no,
            room_type_name: $reservation->room->roomType->name,
            hotel_name: $reservation->room->roomType->hotel->name,
        );
    }
}
