<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class ReservationFormResponseHotel extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
