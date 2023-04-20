<?php

namespace Tests\Feature;

use App\Exceptions\DataInvalidException;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoomTypeCreateTest extends TestCase
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

        $this->get('/admin/room_types/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('RoomType/Form')
                ->missing('roomType')
                ->where('hotels', $hotels));
    }

    public function testCanCreateRoomType(): void
    {
        $roomType = RoomType::factory()->make();
        if (!($roomType instanceof RoomType)) {
            throw new DataInvalidException();
        }
        $input = $roomType->only('hotel_id', 'name', 'price');

        Hotel::factory()->create(['id' => $roomType->hotel_id]);

        $this->post('/admin/room_types', $input)
            ->assertRedirect()
            ->assertSessionMissing('errors')
            ->assertSessionHas('success', 'Room type created.');

        $this->assertDatabaseHas('room_types', $input);
    }
}
