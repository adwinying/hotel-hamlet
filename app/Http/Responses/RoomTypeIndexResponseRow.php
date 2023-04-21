<?php

namespace App\Http\Responses;

use App\Exceptions\DataInvalidException;
use App\Models\RoomType;
use Spatie\LaravelData\Data;

class RoomTypeIndexResponseRow extends Data
{
    public function __construct(
        public int $id,
        public int $hotel_id,
        public string $name,
        public string $hotel_name,
    ) {
    }

    public static function fromModel(RoomType $roomType): self
    {
        if ($roomType->hotel === null) {
            throw new DataInvalidException();
        }

        return new self(
            id: $roomType->id,
            hotel_id: $roomType->hotel_id,
            name: $roomType->name,
            hotel_name: $roomType->hotel->name,
        );
    }
}
