<?php

namespace Tests\Feature\Actions\RoomType;

use App\Actions\RoomType\FilterRoomType;
use App\Models\RoomType;
use Tests\TestCase;

class FilterRoomTypeTest extends TestCase
{
    public function testFilterHotelIdParam()
    {
        $query  = RoomType::query();
        $params = ['hotel_id' => mt_rand(1, 99)];

        $expectedSql      = 'select * from `room_types` where `room_types`.`hotel_id` = ? and `room_types`.`deleted_at` is null';
        $expectedBindings = [$params['hotel_id']];

        $filter = app(FilterRoomType::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterNameParam()
    {
        $query  = RoomType::query();
        $params = ['name' => 'foo'];

        $expectedSql      = 'select * from `room_types` where `room_types`.`name` like ? and `room_types`.`deleted_at` is null';
        $expectedBindings = ["%{$params['name']}%"];

        $filter = app(FilterRoomType::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterMultipleParams()
    {
        $query  = RoomType::query();
        $params = [
            'name'     => 'foo',
            'hotel_id' => mt_rand(1, 99),
        ];

        $expectedSql      = 'select * from `room_types` where `room_types`.`name` like ? and `room_types`.`hotel_id` = ? and `room_types`.`deleted_at` is null';
        $expectedBindings = [
            "%{$params['name']}%",
            $params['hotel_id'],
        ];

        $filter = app(FilterRoomType::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }
}
