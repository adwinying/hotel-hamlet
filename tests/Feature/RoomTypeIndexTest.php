<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoomTypeIndexTest extends TestCase
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

        $roomTypes = RoomType::factory()->count(3)->create([
            'hotel_id' => $hotels->random()['id'],
        ])->map($this->formatRoomTypeResult(...));

        $this->get('/admin/room_types')->assertInertia(fn (Assert $page) => $page
            ->component('RoomType/Index')
            ->has('query', fn (Assert $query) => $query
                ->has('hotel_id')
                ->has('name'))
            ->where('result.data', $roomTypes)
            ->where('hotels', $hotels));
    }

    public function testCanFilterByHotelId(): void
    {
        $roomTypes = RoomType::factory()->count(3)->create();

        $roomType = $roomTypes->random();
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/room_types?hotel_id={$roomType->hotel_id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('RoomType/Index')
                ->has('query', fn (Assert $query) => $query
                    ->where('hotel_id', (string) $roomType->hotel_id)
                    ->has('name'))
                ->where('result.data', $roomTypes
                    ->where('hotel_id', $roomType->hotel_id)
                    ->values()
                    ->map($this->formatRoomTypeResult(...))));
    }

    public function testCanFilterByName(): void
    {
        $roomTypes = RoomType::factory()->count(3)->create();

        $roomType = $roomTypes->random();
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/room_types?name={$roomType->name}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('RoomType/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->where('name', (string) $roomType->name))
                ->where('result.data', $roomTypes
                    ->where('name', $roomType->name)
                    ->values()
                    ->map($this->formatRoomTypeResult(...))));
    }

    /**
     * @return array<string,mixed>
     */
    public function formatRoomTypeResult(RoomType $roomType): array
    {
        return [
            'id'         => $roomType->id,
            'hotel_id'   => $roomType->hotel_id,
            'name'       => $roomType->name,
            'hotel_name' => $roomType->hotel?->name,
        ];
    }
}
