<?php

namespace Tests\Feature;

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

    public function testReturnsErrorWhenRoomTypeIdNotSpecified()
    {
        $checkInDate  = today()->addMonth();
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?check_in_date=$checkInDate&check_out_date=$checkOutDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenCheckInDateNotSpecified()
    {
        $roomTypeId   = mt_rand(1, 10);
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_out_date=$checkOutDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenCheckOutDateNotSpecified()
    {
        $roomTypeId  = mt_rand(1, 10);
        $checkInDate = today()->addMonth();

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate")
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ]);
    }

    public function testReturnsErrorWhenRoomTypeIdDoesNotExist()
    {
        $roomTypeId   = mt_rand(1, 10);
        $checkInDate  = today()->addMonth();
        $checkOutDate = today()->addMonth()->addDays(5);

        $this->json('GET', "/api/admin/room_availability?room_type_id=$roomTypeId&check_in_date=$checkInDate&check_out_date=$checkOutDate")
            ->assertStatus(404);
    }

    public function testReturnsListOfAvailableRoomsForBooking()
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
}
