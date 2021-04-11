<?php

namespace App\Actions\Hotel;

use App\Actions\FilterModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilterHotel
{
    protected $filter;

    public function __construct(FilterModel $filterModel)
    {
        $this->filter = $filterModel;
    }

    /**
     * Filter hotel params
     * @param array $params Parameters to filter
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function execute(QueryBuilder $query, array $params = []): QueryBuilder
    {
        $exactKeys = [
            'is_hidden',
        ];

        $this->filter->execute($query, $params, $exactKeys);

        return $query;
    }
}
