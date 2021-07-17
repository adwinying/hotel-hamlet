<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class RoomTypeEditTest extends TestCase
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
        $roomType   = RoomType::factory()->create();
        $roomTypeId = $roomType->id;

        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name');

        $this->get("/admin/room_types/$roomTypeId")
            ->assertInertia(fn (Assert $page) => $page
                ->component('RoomType/Form')
                ->has('roomType', fn (Assert $page) => $page
                    ->where('id', $roomType->id)
                    ->where('hotel_id', $roomType->hotel_id)
                    ->where('name', $roomType->name)
                    ->where('price', $roomType->price))
                ->where('hotels', $hotels));
    }

    public function testCanUpdateHotelId()
    {
        $roomType   = RoomType::factory()->create();
        $roomTypeId = $roomType->id;

        $hotelId = mt_rand(1, 99);
        Hotel::factory()->create(['id' => $hotelId]);

        $input = [
            'hotel_id' => $hotelId,
            'name'     => $roomType->name,
            'price'    => $roomType->price,
        ];

        $this->from("/admin/room_types/$roomTypeId")
            ->put("/admin/room_types/$roomTypeId", $input)
            ->assertRedirect("/admin/room_types/$roomTypeId")
            ->assertSessionHas('success', 'Room type updated.');

        $this->assertDatabaseHas('room_types', [
            'id'       => $roomTypeId,
            'hotel_id' => $hotelId,
        ]);
    }

    public function testCanUpdateRoomTypeName()
    {
        $roomType   = RoomType::factory()->create();
        $roomTypeId = $roomType->id;

        Hotel::factory()->create(['id' => $roomType->hotel_id]);

        $input = [
            'hotel_id' => $roomType->hotel_id,
            'name'     => 'new name',
            'price'    => $roomType->price,
        ];

        $this->from("/admin/room_types/$roomTypeId")
            ->put("/admin/room_types/$roomTypeId", $input)
            ->assertRedirect("/admin/room_types/$roomTypeId")
            ->assertSessionHas('success', 'Room type updated.');

        $this->assertDatabaseHas('room_types', [
            'id'   => $roomTypeId,
            'name' => $input['name'],
        ]);
    }

    public function testCanUpdatePrice()
    {
        $roomType   = RoomType::factory()->create();
        $roomTypeId = $roomType->id;

        Hotel::factory()->create(['id' => $roomType->hotel_id]);

        $input = [
            'hotel_id' => $roomType->hotel_id,
            'name'     => $roomType->name,
            'price'    => mt_rand(0, 99999),
        ];

        $this->from("/admin/room_types/$roomTypeId")
            ->put("/admin/room_types/$roomTypeId", $input)
            ->assertRedirect("/admin/room_types/$roomTypeId")
            ->assertSessionHas('success', 'Room type updated.');

        $this->assertDatabaseHas('room_types', [
            'id'    => $roomTypeId,
            'price' => $input['price'],
        ]);
    }

    public function testCanDelete()
    {
        $roomType   = RoomType::factory()->create();
        $roomTypeId = $roomType->id;

        $this->from("/admin/room_types/$roomTypeId")
            ->delete("/admin/room_types/$roomTypeId")
            ->assertRedirect('/admin/room_types')
            ->assertSessionHas('success', 'Room type deleted.');

        $this->assertDatabaseMissing('room_types', [
            'id'         => $roomTypeId,
            'deleted_at' => null,
        ]);
    }
}
