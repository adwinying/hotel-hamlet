<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RoomFormRequest extends Data
{
    public function __construct(
        #[Exists('room_types', 'id')]
        public int $room_type_id,
        public string $room_no,
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
            'room_no' => [
                'required',
                'max:255',
                Rule::unique('rooms')
                    ->where(fn ($q) => $q->whereRoomTypeId($payload['room_type_id'] ?? null))
                    ->ignore(request()->route('room')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'room_type_id' => 'Room Type',
        ];
    }
}
