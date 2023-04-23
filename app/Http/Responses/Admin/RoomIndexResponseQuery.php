<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class RoomIndexResponseQuery extends Data
{
    public function __construct(
        public ?string $hotel_id,
        public ?string $room_type_id,
        public ?string $room_no,
    ) {
    }
}
