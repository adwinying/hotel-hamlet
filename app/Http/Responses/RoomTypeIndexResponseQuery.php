<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class RoomTypeIndexResponseQuery extends Data
{
    public function __construct(
        public ?string $hotel_id,
        public ?string $name,
    ) {
    }
}
