<?php

namespace App\Actions\RoomType;

use App\Actions\FilterModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilterRoomType
{
    protected $filter;

    public function __construct(FilterModel $filterModel)
    {
        $this->filter = $filterModel;
    }

    /**
     * Filter room type params
     * @param array $params Parameters to filter
     * @return Illuminate\Database\Eloquent\Builder
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
