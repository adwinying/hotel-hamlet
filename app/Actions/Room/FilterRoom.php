<?php

namespace App\Actions\Room;

use App\Actions\FilterModel;
use Arr;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilterRoom
{
    protected $filter;

    public function __construct(FilterModel $filterModel)
    {
        $this->filter = $filterModel;
    }

    /**
     * Filter room params
     * @param array $params Parameters to filter
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'room_type_id',
        ];

        if ($value = Arr::pull($params, 'hotel_id')) {
            $this->filterHotelId($query, $value);
        }

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }

    protected function filterHotelId(QueryBuilder $query, int $value)
    {
        $query->whereHas(
            'roomType',
            fn ($q) => $q->whereHotelId($value)
        );
    }
}
