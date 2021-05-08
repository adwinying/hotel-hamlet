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
                    ->where('name', $roomType->name))
                ->where('hotels', $hotels));
    }
}
