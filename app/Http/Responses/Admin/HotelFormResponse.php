<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class HotelFormResponse extends Data
{
    public function __construct(
        public ?HotelFormResponseHotel $hotel,
    ) {
    }
}
