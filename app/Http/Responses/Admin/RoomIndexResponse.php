<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class RoomIndexResponse extends Data
{
    /**
     * @param PaginatedDataCollection<array-key,HotelIndexResponseRow> $result
     * @param DataCollection<array-key,RoomIndexResponseHotel>         $hotels
     * @param DataCollection<array-key,RoomIndexResponseRoomType>      $roomTypes
     */
    public function __construct(
        public RoomIndexResponseQuery $query,
        #[DataCollectionOf(RoomIndexResponseRow::class)]
        public PaginatedDataCollection $result,
        #[DataCollectionOf(RoomIndexResponseHotel::class)]
        public DataCollection $hotels,
        #[DataCollectionOf(RoomIndexResponseRoomType::class)]
        public DataCollection $roomTypes,
    ) {
    }
}
