<?php

namespace App\Http\Requests\Admin;

use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class RoomAvailabilityCheckRequest extends Data
{
    public function __construct(
        #[Exists('room_types', 'id')]
        public int $room_type_id,
        #[DateFormat('Y-m-d')]
        public string $check_in_date,
        #[DateFormat('Y-m-d')]
        public string $check_out_date,
        #[Exists('reservations', 'id')]
        public ?int $reservation_id = null,
    ) {
    }
}
