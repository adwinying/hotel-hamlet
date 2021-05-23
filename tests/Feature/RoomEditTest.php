<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
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

    public function testCanShowPage()
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
                    ->where('hotel_id', $room->roomType->hotel_id)
                    ->where('room_type_id', $room->room_type_id)
                    ->where('room_no', $room->room_no))
                ->where('hotels', $hotels)
                ->where('roomTypes', $roomTypes));
    }
}
