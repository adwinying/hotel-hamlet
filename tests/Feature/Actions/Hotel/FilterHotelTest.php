<?php

namespace Tests\Feature\Actions\Hotel;

use App\Actions\Hotel\FilterHotel;
use App\Models\Hotel;
use Tests\TestCase;

class FilterHotelTest extends TestCase
{
    public function testFilterNameParam(): void
    {
        $query  = Hotel::query();
        $params = ['name' => 'foo'];

        $expectedSql      = 'select * from `hotels` where `hotels`.`name` like ? and `hotels`.`deleted_at` is null';
        $expectedBindings = ["%{$params['name']}%"];

        $filter = app(FilterHotel::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterIsHiddenParam(): void
    {
        $query  = Hotel::query();
        $params = ['is_hidden' => true];

        $expectedSql      = 'select * from `hotels` where `hotels`.`is_hidden` = ? and `hotels`.`deleted_at` is null';
        $expectedBindings = [$params['is_hidden']];

        $filter = app(FilterHotel::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterMultipleParams(): void
    {
        $query  = Hotel::query();
        $params = [
            'name'      => 'foo',
            'is_hidden' => true,
        ];

        $expectedSql      = 'select * from `hotels` where `hotels`.`name` like ? and `hotels`.`is_hidden` = ? and `hotels`.`deleted_at` is null';
        $expectedBindings = [
            "%{$params['name']}%",
            $params['is_hidden'],
        ];

        $filter = app(FilterHotel::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }
}
