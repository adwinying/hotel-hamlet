<?php

namespace App\Http\Responses;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\PaginatedDataCollection;

class HotelIndexResponse extends Data
{
    /**
     * @param PaginatedDataCollection<array-key,HotelIndexResponseRow> $result
     */
    public function __construct(
        public HotelIndexResponseQuery $query,
        #[DataCollectionOf(HotelIndexResponseRow::class)]
        public PaginatedDataCollection $result,
    ) {
    }
}
