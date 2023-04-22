<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Data;

class HotelIndexResponseRow extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $is_hidden,
    ) {
    }
}
