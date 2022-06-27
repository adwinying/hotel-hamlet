<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class FilterModel
{
    /**
     * Filter Models
     *
     * @template T of Model
     *
     * @param QueryBuilder<T>        $query     QueryBuilder
     * @param array<string, ?scalar> $params    Parameters to filter (LIKE search)
     * @param array<int, string>     $exactKeys Parameters to filter (= search)
     * @return QueryBuilder<T>
     */
    public function execute(QueryBuilder $query, array $params = [], array $exactKeys = []): QueryBuilder
    {
        $params = array_filter($params, fn ($searchVal) => $searchVal !== null);

        $table = $query->getModel()->getTable();

        $this->addFilters($query, $table, Arr::except($params, $exactKeys));
        $this->addExactFilters($query, $table, Arr::only($params, $exactKeys));

        return $query;
    }

    /**
     * @param QueryBuilder<Model>    $query  Query builder
     * @param string                 $table  Table name
     * @param array<string, ?scalar> $params Params to filter
     */
    protected function addFilters(QueryBuilder $query, string $table, array $params): void
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

    /**
     * @param QueryBuilder<Model>    $query  Query builder
     * @param string                 $table  Table name
     * @param array<string, ?scalar> $params Params to filter
     */
    protected function addExactFilters(QueryBuilder $query, string $table, array $params): void
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
