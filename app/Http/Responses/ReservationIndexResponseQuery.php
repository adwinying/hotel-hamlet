<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class ReservationIndexResponseQuery extends Data
{
    public function __construct(
        public ?string $check_in_date,
        public ?string $check_out_date,
        public ?string $guest_name,
        public ?string $guest_email,
        public ?string $hotel_id,
        public ?string $room_type_id,
    ) {
    }
}
