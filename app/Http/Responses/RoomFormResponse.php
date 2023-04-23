<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class RoomFormResponse extends Data
{
    /**
     * @param DataCollection<array-key,RoomFormResponseHotel>    $hotels
     * @param DataCollection<array-key,RoomFormResponseRoomType> $roomTypes
     */
    public function __construct(
        #[DataCollectionOf(RoomFormResponseHotel::class)]
        public DataCollection $hotels,
        #[DataCollectionOf(RoomFormResponseRoomType::class)]
        public DataCollection $roomTypes,
        public ?RoomFormResponseRoom $room,
    ) {
    }
}
