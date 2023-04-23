<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class ReservationFormResponseHotel extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}