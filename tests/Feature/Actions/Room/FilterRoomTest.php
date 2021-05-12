<?php

namespace Tests\Feature\Actions\Room;

use App\Actions\Room\FilterRoom;
use App\Models\Room;
use Tests\TestCase;

class FilterRoomTest extends TestCase
{
    public function testFilterRoomNoParam()
    {
        $query  = Room::query();
        $params = ['room_no' => mt_rand(100, 999)];

        $expectedSql      = 'select * from `rooms` where `rooms`.`room_no` like ? and `rooms`.`deleted_at` is null';
        $expectedBindings = ["%{$params['room_no']}%"];

        $filter = app(FilterRoom::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterRoomTypeIdParam()
    {
        $query  = Room::query();
        $params = ['room_type_id' => mt_rand(1, 99)];

        $expectedSql      = 'select * from `rooms` where `rooms`.`room_type_id` = ? and `rooms`.`deleted_at` is null';
        $expectedBindings = [$params['room_type_id']];

        $filter = app(FilterRoom::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterHotelIdParam()
    {
        $query  = Room::query();
        $params = ['hotel_id' => mt_rand(1, 99)];

        $expectedSql      = 'select * from `rooms` where exists (select * from `room_types` where `rooms`.`room_type_id` = `room_types`.`id` and `hotel_id` = ? and `room_types`.`deleted_at` is null) and `rooms`.`deleted_at` is null';
        $expectedBindings = [$params['hotel_id']];

        $filter = app(FilterRoom::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterMultipleParams()
    {
        $query  = Room::query();
        $params = [
            'room_no'      => mt_rand(100, 999),
            'room_type_id' => mt_rand(1, 99),
            'hotel_id'     => mt_rand(1, 99),
        ];

        $expectedSql      = 'select * from `rooms` where exists (select * from `room_types` where `rooms`.`room_type_id` = `room_types`.`id` and `hotel_id` = ? and `room_types`.`deleted_at` is null) and `rooms`.`room_no` like ? and `rooms`.`room_type_id` = ? and `rooms`.`deleted_at` is null';
        $expectedBindings = [
            $params['hotel_id'],
            "%{$params['room_no']}%",
            $params['room_type_id'],
        ];

        $filter = app(FilterRoom::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }
}
