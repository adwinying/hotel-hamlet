<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoomCreateTest extends TestCase
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

        $this->get('/admin/rooms/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Room/Form')
                ->missing('room')
                ->where('hotels', $hotels)
                ->where('roomTypes', $roomTypes));
    }

    public function testCanCreateRoom(): void
    {
        $room = Room::factory()->make();
        assert($room instanceof Room);
        $input = $room->only('room_type_id', 'room_no');

        RoomType::factory()->create(['id' => $room->room_type_id]);

        $this->post('/admin/rooms', $input)
            ->assertRedirect()
            ->assertSessionMissing('errors')
            ->assertSessionHas('success', 'Room created.');

        $this->assertDatabaseHas('rooms', $input);
    }
}
