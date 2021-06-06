<?php

namespace App\Actions\Reservation;

use App\Actions\FilterModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class FilterReservation
{
    protected $filter;

    public function __construct(FilterModel $filterModel)
    {
        $this->filter = $filterModel;
    }

    /**
     * Filter reservation params
     * @param array $params Parameters to filter
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'check_in_date',
            'check_out_date',
        ];

        if ($value = Arr::pull($params, 'hotel_id')) {
            $this->filterHotelId($query, $value);
        }

        if ($value = Arr::pull($params, 'room_type_id')) {
            $this->filterRoomTypeId($query, $value);
        }

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }

    protected function filterHotelId(QueryBuilder $query, int $value)
    {
        $query->whereHas('room.roomType', fn ($q) => $q->whereHotelId($value));
    }

    protected function filterRoomTypeId(QueryBuilder $query, int $value)
    {
        $query->whereHas('room', fn ($q) => $q->whereRoomTypeId($value));
    }
}
