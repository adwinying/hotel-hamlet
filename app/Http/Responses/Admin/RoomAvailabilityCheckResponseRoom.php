<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class RoomAvailabilityCheckResponseRoom extends Data
{
    public function __construct(
        public int $id,
        public int $room_type_id,
        public string $room_no,
    ) {
    }
}
