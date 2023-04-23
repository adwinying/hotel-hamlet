<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class RoomTypeFormResponseRoomType extends Data
{
    public function __construct(
        public int $id,
        public int $hotel_id,
        public string $name,
        public int $price,
    ) {
    }
}
