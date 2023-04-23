<?php

namespace App\Http\Requests\Admin;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class HotelIndexRequest extends Data
{
    public function __construct(
        public string $sort = 'id',
        #[In(['asc', 'desc'])]
        public string $order = 'asc',
        public int $count = 20,
        public int $page = 1,
        public ?string $name = null,
        public ?bool $is_hidden = null,
    ) {
    }
}
