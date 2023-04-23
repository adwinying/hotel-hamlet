<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class HotelFormResponse extends Data
{
    public function __construct(
        public ?HotelFormResponseHotel $hotel,
    ) {
    }
}
