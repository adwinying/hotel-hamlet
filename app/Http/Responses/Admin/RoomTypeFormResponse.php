<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class RoomTypeFormResponse extends Data
{
    /**
     * @param DataCollection<array-key,RoomTypeFormResponseHotel> $hotels
     */
    public function __construct(
        #[DataCollectionOf(RoomTypeFormResponseHotel::class)]
        public DataCollection $hotels,
        public ?RoomTypeFormResponseRoomType $roomType,
    ) {
    }
}
