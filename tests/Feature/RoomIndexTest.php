<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class RoomIndexTest extends TestCase
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

        $rooms = Room::factory()->count(3)->create()
            ->map([$this, 'formatRoomResult']);

        $this->get('/admin/rooms')->assertInertia(fn (Assert $page) => $page
            ->component('Room/Index')
            ->has('query', fn (Assert $query) => $query
                ->has('hotel_id')
                ->has('room_type_id')
                ->has('room_no'))
            ->where('result.data', $rooms)
            ->where('hotels', $hotels)
            ->where('roomTypes', $roomTypes));
    }

    public function testCanFilterByHotelId()
    {
        $rooms = Room::factory()->count(3)->create();

        $room     = $rooms->random();
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);

        $this->get("/admin/rooms?hotel_id={$roomType->hotel_id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Room/Index')
                ->has('query', fn (Assert $query) => $query
                    ->where('hotel_id', (string) $roomType->hotel_id)
                    ->has('room_type_id')
                    ->has('room_no'))
                ->where('result.data', $rooms
                    ->where('room_type_id', $room->room_type_id)
                    ->values()
                    ->map([$this, 'formatRoomResult'])));
    }

    public function testCanFilterByRoomTypeId()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->random();

        $this->get("/admin/rooms?room_type_id={$room->room_type_id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Room/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->where('room_type_id', (string) $room->room_type_id)
                    ->has('room_no'))
                ->where('result.data', $rooms
                    ->where('room_type_id', $room->room_type_id)
                    ->values()
                    ->map([$this, 'formatRoomResult'])));
    }

    public function testCanFilterByRoomNo()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->random();

        $this->get("/admin/rooms?room_no={$room->room_no}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Room/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->has('room_type_id')
                    ->where('room_no', $room->room_no))
                ->where('result.data', $rooms
                    ->where('room_no', $room->room_no)
                    ->values()
                    ->map([$this, 'formatRoomResult'])));
    }

    public function formatRoomResult(Room $room): array
    {
        return [
            'id'           => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_no'      => $room->room_no,
            'room_type'    => $room->roomType
                ? [
                    'id'       => $room->roomType->id,
                    'hotel_id' => $room->roomType->hotel_id,
                    'name'     => $room->roomType->name,
                    'hotel'    => $room->roomType->hotel
                        ? [
                            'id'   => $room->roomType->hotel->id,
                            'name' => $room->roomType->hotel->name,
                        ]
                        : null,
                ]
                : null,
        ];
    }
}
