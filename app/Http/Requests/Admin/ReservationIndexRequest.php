<?php

namespace App\Http\Requests\Admin;

use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class ReservationIndexRequest extends Data
{
    public function __construct(
        public string $sort = 'id',
        #[In(['asc', 'desc'])]
        public string $order = 'asc',
        public int $count = 20,
        public int $page = 1,
        #[DateFormat('Y-m-d')]
        public ?string $check_in_date = null,
        #[DateFormat('Y-m-d')]
        public ?string $check_out_date = null,
        public ?string $guest_name = null,
        public ?string $guest_email = null,
        public ?int $hotel_id = null,
        public ?int $room_type_id = null,
    ) {
    }
}
