<?php

namespace App\Http\Requests\Admin;

use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class ReservationFormRequest extends Data
{
    public function __construct(
        #[DateFormat('Y-m-d')]
        public string $check_in_date,
        #[DateFormat('Y-m-d'),
    After('check_in_date')]
        public string $check_out_date,
        #[Exists('rooms', 'id', withoutTrashed: true)]
        public int $room_id,
        #[Max(255)]
        public string $guest_name,
        #[Email,
    Max(255)]
        public string $guest_email,
        #[Max(1000)]
        public ?string $remarks,
    ) {
    }
}
