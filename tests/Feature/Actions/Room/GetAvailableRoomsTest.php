<?php

namespace Tests\Feature\Actions\Room;

use App\Actions\Room\GetAvailableRooms;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAvailableRoomsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Existing: |----|
     * Search  :    |----|
     */
    public function testDontReturnRoomsWithOverlappingCheckInDate(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(mt_rand(1, 5));

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkInDate->clone()->subDays(2),
                'check_out_date' => $checkInDate->clone()->addDays(2),
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEmpty($result);
    }

    /**
     * Existing:   |----|
     * Search  : |----|
     */
    public function testDontReturnRoomsWithOverlappingCheckOutDate(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(mt_rand(1, 5));

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkOutDate->clone()->subDays(2),
                'check_out_date' => $checkOutDate->clone()->addDays(2),
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEmpty($result);
    }

    /**
     * Existing:   |----|
     * Search  : |---------|
     */
    public function testDontReturnRoomsWithinCheckInAndCheckOutDates(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(5);

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkInDate->clone()->addDay(),
                'check_out_date' => $checkOutDate->clone()->subDay(),
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEmpty($result);
    }

    /**
     * Existing: |---------|
     * Search  :   |----|
     */
    public function testDontReturnRoomsOutsideCheckInAndCheckOutDates(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(5);

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkInDate->clone()->subDay(),
                'check_out_date' => $checkOutDate->clone()->addDay(),
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEmpty($result);
    }

    /**
     * Existing: |-----|
     * Search  :       |----|
     */
    public function testReturnRoomsBeforeCheckInDate(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(5);

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkInDate->clone()->subDays(5),
                'check_out_date' => $checkInDate,
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEquals(
            $roomType->rooms->pluck('id'),
            $result->pluck('id')
        );
    }

    /**
     * Existing:      |-----|
     * Search  : |----|
     */
    public function testReturnRoomsAfterCheckOutDate(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(5);

        $roomType = RoomType::factory()->hasRooms()->create();

        Reservation::factory()
            ->for($roomType->rooms->first())
            ->create([
                'check_in_date'  => $checkOutDate,
                'check_out_date' => $checkOutDate->clone()->addDays(5),
            ]);

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEquals(
            $roomType->rooms->pluck('id'),
            $result->pluck('id')
        );
    }

    public function testEmptyReservations(): void
    {
        $checkInDate  = today()->addMonths(mt_rand(1, 5));
        $checkOutDate = $checkInDate->clone()->addDays(5);

        $roomType = RoomType::factory()->hasRooms(3)->create();

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate
        );
        $this->assertEquals(
            $roomType->rooms->pluck('id'),
            $result->pluck('id')
        );
    }

    public function testIncludesExistingReservation(): void
    {
        $roomType = RoomType::factory()->hasRooms()->create();
        $room     = $roomType->rooms->first();

        $reservation  = Reservation::factory()->for($room)->create();
        $checkInDate  = $reservation->check_in_date;
        $checkOutDate = $reservation->check_out_date;

        $getAvailableRooms = app(GetAvailableRooms::class);

        $result = $getAvailableRooms->execute(
            $roomType,
            $checkInDate,
            $checkOutDate,
            $reservation
        );
        $this->assertEquals(
            $roomType->rooms->pluck('id'),
            $result->pluck('id')
        );
    }
}
