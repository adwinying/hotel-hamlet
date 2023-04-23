<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class RoomTypeIndexResponse extends Data
{
    /**
     * @param PaginatedDataCollection<array-key,HotelIndexResponseRow> $result
     * @param DataCollection<array-key,RoomTypeIndexResponseHotel>     $hotels
     */
    public function __construct(
        public RoomTypeIndexResponseQuery $query,
        #[DataCollectionOf(RoomTypeIndexResponseRow::class)]
        public PaginatedDataCollection $result,
        #[DataCollectionOf(RoomTypeIndexResponseHotel::class)]
        public DataCollection $hotels,
    ) {
    }
}
