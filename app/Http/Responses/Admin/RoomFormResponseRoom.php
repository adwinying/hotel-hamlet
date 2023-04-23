<?php

namespace App\Http\Responses\Admin;

use App\Exceptions\DataInvalidException;
use App\Models\Room;
use Spatie\LaravelData\Data;

class RoomFormResponseRoom extends Data
{
    public function __construct(
        public int $id,
        public int $hotel_id,
        public int $room_type_id,
        public string $room_no,
    ) {
    }

    public static function fromModel(Room $room): self
    {
        if ($room->roomType === null) {
            throw new DataInvalidException();
        }

        return new self(
            id: $room->id,
            hotel_id: $room->roomType->hotel_id,
            room_type_id: $room->room_type_id,
            room_no: $room->room_no,
        );
    }
}
