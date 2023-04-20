<?php

namespace Tests\Feature\Actions\Reservation;

use App\Actions\Reservation\FilterReservation;
use App\Models\Reservation;
use Tests\TestCase;

class FilterReservationTest extends TestCase
{
    public function testFilterGuestNameParam(): void
    {
        $query  = Reservation::query();
        $params = ['guest_name' => 'foobar'];

        $expectedSql      = 'select * from `reservations` where `reservations`.`guest_name` like ? and `reservations`.`deleted_at` is null';
        $expectedBindings = ["%{$params['guest_name']}%"];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterGuestEmailParam(): void
    {
        $query  = Reservation::query();
        $params = ['guest_email' => 'foo@bar.com'];

        $expectedSql      = 'select * from `reservations` where `reservations`.`guest_email` like ? and `reservations`.`deleted_at` is null';
        $expectedBindings = ["%{$params['guest_email']}%"];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterRoomIdParam(): void
    {
        $query  = Reservation::query();
        $params = ['room_id' => mt_rand(1, 10)];

        $expectedSql      = 'select * from `reservations` where `reservations`.`room_id` = ? and `reservations`.`deleted_at` is null';
        $expectedBindings = [$params['room_id']];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterCheckInDateParam(): void
    {
        $query  = Reservation::query();
        $params = ['check_in_date' => '2020-01-01'];

        $expectedSql      = 'select * from `reservations` where `reservations`.`check_in_date` = ? and `reservations`.`deleted_at` is null';
        $expectedBindings = [$params['check_in_date']];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterCheckOutDateParam(): void
    {
        $query  = Reservation::query();
        $params = ['check_out_date' => '2020-01-01'];

        $expectedSql      = 'select * from `reservations` where `reservations`.`check_out_date` = ? and `reservations`.`deleted_at` is null';
        $expectedBindings = [$params['check_out_date']];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterHotelIdParam(): void
    {
        $query  = Reservation::query();
        $params = ['hotel_id' => mt_rand(1, 99)];

        $expectedSql      = 'select * from `reservations` where exists (select * from `rooms` where `reservations`.`room_id` = `rooms`.`id` and exists (select * from `room_types` where `rooms`.`room_type_id` = `room_types`.`id` and `hotel_id` = ? and `room_types`.`deleted_at` is null) and `rooms`.`deleted_at` is null) and `reservations`.`deleted_at` is null';
        $expectedBindings = [$params['hotel_id']];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterRoomTypeIdParam(): void
    {
        $query  = Reservation::query();
        $params = ['room_type_id' => mt_rand(1, 99)];

        $expectedSql      = 'select * from `reservations` where exists (select * from `rooms` where `reservations`.`room_id` = `rooms`.`id` and `room_type_id` = ? and `rooms`.`deleted_at` is null) and `reservations`.`deleted_at` is null';
        $expectedBindings = [$params['room_type_id']];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }

    public function testFilterMultipleParams(): void
    {
        $query  = Reservation::query();
        $params = [
            'guest_name'    => 'foobar',
            'check_in_date' => '2020-01-01',
            'room_type_id'  => mt_rand(1, 99),
        ];

        $expectedSql      = 'select * from `reservations` where exists (select * from `rooms` where `reservations`.`room_id` = `rooms`.`id` and `room_type_id` = ? and `rooms`.`deleted_at` is null) and `reservations`.`guest_name` like ? and `reservations`.`check_in_date` = ? and `reservations`.`deleted_at` is null';
        $expectedBindings = [
            $params['room_type_id'],
            "%{$params['guest_name']}%",
            $params['check_in_date'],
        ];

        $filter = app(FilterReservation::class);
        $result = $filter->execute($query, $params);

        $this->assertEquals($expectedSql, $result->toSql());
        $this->assertEquals($expectedBindings, $result->getBindings());
    }
}
