<?php

namespace App\Http\Responses\Admin;

use App\Exceptions\DataInvalidException;
use App\Models\Room;
use Spatie\LaravelData\Data;

class RoomIndexResponseRow extends Data
{
    public function __construct(
        public int $id,
        public int $room_type_id,
        public string $room_no,
        public string $room_type_name,
        public string $hotel_name,
    ) {
    }

    public static function fromModel(Room $room): self
    {
        if ($room->roomType === null ||
            $room->roomType->hotel === null
        ) {
            throw new DataInvalidException();
        }

        return new self(
            id: $room->id,
            room_type_id: $room->room_type_id,
            room_no: $room->room_no,
            room_type_name: $room->roomType->name,
            hotel_name: $room->roomType->hotel->name,
        );
    }
}
