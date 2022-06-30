<?php

namespace App\Actions\Reservation;

use App\Actions\FilterModel;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class FilterReservation
{
    public function __construct(
        protected FilterModel $filter,
    ) {
    }

    /**
     * Filter reservation params
     *
     * @param QueryBuilder<Reservation> $query  Query builder
     * @param array<string, mixed>      $params Parameters to filter
     * @return QueryBuilder<Reservation>
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'check_in_date',
            'check_out_date',
            'room_id',
        ];

        /** @var ?int */
        $value = Arr::pull($params, 'hotel_id');
        if ($value) {
            $this->filterHotelId($query, $value);
        }

        /** @var ?int */
        $value = Arr::pull($params, 'room_type_id');
        if ($value) {
            $this->filterRoomTypeId($query, $value);
        }

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }

    /**
     * @param QueryBuilder<Reservation> $query Query builder
     */
    protected function filterHotelId(QueryBuilder $query, int $value): void
    {
        $query->whereHas('room.roomType', fn ($q) => $q->whereHotelId($value));
    }

    /**
     * @param QueryBuilder<Reservation> $query Query builder
     */
    protected function filterRoomTypeId(QueryBuilder $query, int $value): void
    {
        $query->whereHas('room', fn ($q) => $q->whereRoomTypeId($value));
    }
}
