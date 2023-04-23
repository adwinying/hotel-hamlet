<?php

namespace App\Http\Requests\Admin;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class RoomTypeIndexRequest extends Data
{
    public function __construct(
        public string $sort = 'id',
        #[In(['asc', 'desc'])]
        public string $order = 'asc',
        public int $count = 20,
        public int $page = 1,
        public ?int $hotel_id = null,
        public ?string $name = null,
    ) {
    }
}
