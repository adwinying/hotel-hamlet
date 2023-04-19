<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoomEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testCanShowPage(): void
    {
        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name');

        $roomTypes = RoomType::factory()->count(3)->create()
            ->map
            ->only('id', 'hotel_id', 'name');

        $room = Room::factory()->create([
            'room_type_id' => $roomTypes->random()['id'],
        ]);
        $roomId = $room->id;

        $this->get("/admin/rooms/$roomId")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Room/Form')
                ->has('room', fn (Assert $page) => $page
                    ->where('id', $room->id)
                    ->where('hotel_id', $room->roomType?->hotel_id)
                    ->where('room_type_id', $room->room_type_id)
                    ->where('room_no', $room->room_no))
                ->where('hotels', $hotels)
                ->where('roomTypes', $roomTypes));
    }

    public function testCanUpdateRoomTypeId(): void
    {
        $room   = Room::factory()->create();
        $roomId = $room->id;

        $roomTypeId = mt_rand(1, 99);
        RoomType::factory()->create(['id' => $roomTypeId]);

        $input = [
            'room_type_id' => $roomTypeId,
            'room_no'      => $room->room_no,
        ];

        $this->from("/admin/rooms/$roomId")
            ->put("/admin/rooms/$roomId", $input)
            ->assertRedirect("/admin/rooms/$roomId")
            ->assertSessionHas('success', 'Room updated.');

        $this->assertDatabaseHas('rooms', [
            'id'           => $roomId,
            'room_type_id' => $roomTypeId,
        ]);
    }

    public function testCanUpdateRoomNo(): void
    {
        $room   = Room::factory()->create();
        $roomId = $room->id;

        RoomType::factory()->create(['id' => $room->room_type_id]);

        $input = [
            'room_type_id' => $room->room_type_id,
            'room_no'      => 'new no',
        ];

        $this->from("/admin/rooms/$roomId")
            ->put("/admin/rooms/$roomId", $input)
            ->assertRedirect("/admin/rooms/$roomId")
            ->assertSessionHas('success', 'Room updated.');

        $this->assertDatabaseHas('rooms', [
            'id'      => $roomId,
            'room_no' => $input['room_no'],
        ]);
    }

    public function testCanDelete(): void
    {
        $room   = Room::factory()->create();
        $roomId = $room->id;

        $this->from("/admin/rooms/$roomId")
            ->delete("/admin/rooms/$roomId")
            ->assertRedirect('/admin/rooms')
            ->assertSessionHas('success', 'Room deleted.');

        $this->assertDatabaseMissing('rooms', [
            'id'         => $roomId,
            'deleted_at' => null,
        ]);
    }
}
