<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ReservationIndexResponse extends Data
{
    /**
     * @param PaginatedDataCollection<array-key,HotelIndexResponseRow>   $result
     * @param DataCollection<array-key,ReservationIndexResponseHotel>    $hotels
     * @param DataCollection<array-key,ReservationIndexResponseRoomType> $roomTypes
     */
    public function __construct(
        public ReservationIndexResponseQuery $query,
        #[DataCollectionOf(ReservationIndexResponseRow::class)]
        public PaginatedDataCollection $result,
        #[DataCollectionOf(ReservationIndexResponseHotel::class)]
        public DataCollection $hotels,
        #[DataCollectionOf(ReservationIndexResponseRoomType::class)]
        public DataCollection $roomTypes,
    ) {
    }
}
