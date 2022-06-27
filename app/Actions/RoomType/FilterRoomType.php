<?php

namespace App\Actions\RoomType;

use App\Actions\FilterModel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilterRoomType
{
    public function __construct(
        protected FilterModel $filter,
    ) {
    }

    /**
     * Filter room type params
     *
     * @param QueryBuilder<RoomType> $query  Query builder
     * @param array<string, ?scalar> $params Parameters to filter
     * @return QueryBuilder<RoomType>
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'hotel_id',
        ];

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }
}
