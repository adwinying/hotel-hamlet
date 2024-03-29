<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class HotelIndexResponseQuery extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $is_hidden,
    ) {
    }
}
