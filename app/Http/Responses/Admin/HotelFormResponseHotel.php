<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class HotelFormResponseHotel extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $is_hidden,
    ) {
    }
}
