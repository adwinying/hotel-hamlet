<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityCheckTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testReturnsErrorWhenRoomTypeIdNotSpecified(): void
    {
        $checkInDate  = today()->addMonth();
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?check_in_date=$checkInDate&check_out_date=$checkOutDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenCheckInDateNotSpecified(): void
    {
        $roomTypeId   = mt_rand(1, 10);
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_out_date=$checkOutDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenCheckOutDateNotSpecified(): void
    {
        $roomTypeId  = mt_rand(1, 10);
        $checkInDate = today()->addMonth();

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenRoomTypeIdDoesNotExist(): void
    {
        $roomTypeId   = mt_rand(1, 10);
        $checkInDate  = today()->addMonth();
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate&check_out_date=$checkOutDate")
            ->assertStatus(404);
    }

    public function testReturnsListOfAvailableRoomsForBooking(): void
    {
        $roomType = RoomType::factory()->hasRooms(3)->create();

        $roomTypeId   = $roomType->id;
        $checkInDate  = today()->addMonth();
        $checkOutDate = today()->addMonth()->addDays(5);

        $expected = $roomType->rooms->map(fn ($room) => [
            'id'           => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_no'      => $room->room_no,
        ])->all();

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate&check_out_date=$checkOutDate")
             ->assertStatus(200)
             ->assertExactJson($expected);
    }

    public function testIncludesExistingRoomReservationInResults(): void
    {
        $roomType = RoomType::factory()->hasRooms()->create();
        $room     = $roomType->rooms->first();

        $reservation = Reservation::factory()->for($room)->create();

        $roomTypeId    = $roomType->id;
        $checkInDate   = $reservation->check_in_date->format('Y-m-d');
        $checkOutDate  = $reservation->check_out_date->format('Y-m-d');
        $reservationId = $reservation->id;

        $expected = [[
            'id'           => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_no'      => $room->room_no,
        ]];

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate&check_out_date=$checkOutDate&reservation_id=$reservationId")
             ->assertStatus(200)
             ->assertExactJson($expected);
    }
}
