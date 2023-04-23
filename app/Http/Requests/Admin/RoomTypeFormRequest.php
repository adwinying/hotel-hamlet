<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RoomTypeFormRequest extends Data
{
    public function __construct(
        #[Exists('hotels', 'id')]
        public int $hotel_id,
        public string $name,
        #[IntegerType,
    Max(99_999)]
        public int $price,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(ValidationContext $context): array
    {
        /** @var array<string,mixed> */
        $payload = $context->payload;

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('room_types')
                    ->where(fn ($q) => $q->whereHotelId($payload['hotel_id'] ?? null))
                    ->ignore(request()->route('room_type')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'hotel_id' => 'Hotel',
        ];
    }
}
