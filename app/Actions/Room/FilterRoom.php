<?php

namespace App\Actions\Room;

use App\Actions\FilterModel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class FilterRoom
{
    public function __construct(
        protected FilterModel $filter,
    ) {
    }

    /**
     * Filter room params
     *
     * @param QueryBuilder<Room>   $query  Query builder
     * @param array<string, mixed> $params Parameters to filter
     * @return QueryBuilder<Room>
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'room_type_id',
        ];

        /** @var ?int */
        $value = Arr::pull($params, 'hotel_id');
        if ($value) {
            $this->filterHotelId($query, $value);
        }

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }

    /**
     * @param QueryBuilder<Room> $query Query builder
     */
    protected function filterHotelId(QueryBuilder $query, int $value): void
    {
        $query->whereHas(
            'roomType',
            fn ($q) => $q->whereHotelId($value)
        );
    }
}
