<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class RoomTypeIndexResponseQuery extends Data
{
    public function __construct(
        public ?string $hotel_id,
        public ?string $name,
    ) {
    }
}
