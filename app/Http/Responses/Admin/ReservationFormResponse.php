<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ReservationFormResponse extends Data
{
    /**
     * @param DataCollection<array-key,ReservationFormResponseHotel>    $hotels
     * @param DataCollection<array-key,ReservationFormResponseRoomType> $roomTypes
     */
    public function __construct(
        #[DataCollectionOf(ReservationFormResponseHotel::class)]
        public DataCollection $hotels,
        #[DataCollectionOf(ReservationFormResponseRoomType::class)]
        public DataCollection $roomTypes,
        public ?ReservationFormResponseReservation $reservation,
    ) {
    }
}
