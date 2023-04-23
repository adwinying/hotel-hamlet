<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class RoomTypeFormResponseHotel extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
