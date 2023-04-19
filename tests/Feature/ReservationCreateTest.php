<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReservationCreateTest extends TestCase
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

        $this->get('/admin/reservations/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Form')
                ->missing('reservation')
                ->where('hotels', $hotels)
                ->where('roomTypes', $roomTypes));
    }

    public function testCanCreateReservation(): void
    {
        $room  = Room::factory()->forRoomType()->create();
        $input = Reservation::factory()->make([
            'room_id' => $room->id,
        ])->toArray();

        $this->post('/admin/reservations', $input)
            ->assertRedirect()
            ->assertSessionMissing('errors')
            ->assertSessionHas('success', 'Reservation created.');

        $this->assertDatabaseHas('reservations', $input);
    }

    public function testReturnsErrorWhenRoomUnavailable(): void
    {
        $room  = Room::factory()->forRoomType()->create();
        $input = Reservation::factory()->create([
            'room_id' => $room->id,
        ])->toArray();

        $this->from(route('reservations.create'))
            ->post('/admin/reservations', $input)
            ->assertRedirect(route('reservations.create'))
            ->assertSessionHasErrors([
                'room_id' => 'Selected room is unavailable.',
            ]);
    }
}
