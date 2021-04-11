<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class FilterModel
{
    /**
     * Filter Models
     * @param array $params    Parameters to filter (LIKE search)
     * @param array $exactKeys Parameters to filter (= search)
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function execute(QueryBuilder $query, array $params = [], array $exactKeys = []): QueryBuilder
    {
        $params = array_filter($params, fn ($searchVal) => $searchVal !== null);

        $table = $query->getModel()->getTable();

        $this->addFilters($query, $table, Arr::except($params, $exactKeys));
        $this->addExactFilters($query, $table, Arr::only($params, $exactKeys));

        return $query;
    }

    protected function addFilters(QueryBuilder $query, string $table, array $params)
    {
        foreach ($params as $param => $value) {
            if ($value === 'null') {
                $query->where("$table.$param", null);
                continue;
            }

            if ($value === 0 || $value === false) {
                $query->where("$table.$param", 'like', $value);
                continue;
            }

            $query->where("$table.$param", 'like', "%$value%");
        }
    }

    protected function addExactFilters(QueryBuilder $query, string $table, array $params)
    {
        foreach ($params as $param => $value) {
            if ($value === 'null') {
                $query->where("$table.$param", null);
                continue;
            }

            $query->where("$table.$param", $value);
        }
    }
}
