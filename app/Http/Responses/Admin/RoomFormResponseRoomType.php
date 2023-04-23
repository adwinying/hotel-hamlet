<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class RoomFormResponseRoomType extends Data
{
    public function __construct(
        public int $id,
        public int $hotel_id,
        public string $name,
    ) {
    }
}
