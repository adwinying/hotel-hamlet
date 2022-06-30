<?php

namespace App\Actions\Hotel;

use App\Actions\FilterModel;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilterHotel
{
    public function __construct(
        protected FilterModel $filter,
    ) {
    }

    /**
     * Filter hotel params
     *
     * @param QueryBuilder<Hotel>    $query  Query builder
     * @param array<string, ?scalar> $params Parameters to filter
     * @return QueryBuilder<Hotel>
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
