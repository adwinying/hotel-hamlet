<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class ReservationEditTest extends TestCase
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
        $hotels         = Hotel::factory()->count(3)->create();
        $expectedHotels = $hotels
            ->map
            ->only('id', 'name');

        $roomTypes = RoomType::factory()->count(3)
            ->for($hotels->first())
            ->create();
        $expectedRoomTypes = $roomTypes
            ->map
            ->only('id', 'hotel_id', 'name');

        $room = Room::factory()->for($roomTypes->first())->create();

        $reservation   = Reservation::factory()->for($room)->create();
        $reservationId = $reservation->id;

        $this->get("/admin/reservations/$reservationId")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Form')
                ->has('reservation', fn (Assert $page) => $page->whereAll([
                    'id'             => $reservation->id,
                    'check_in_date'  => $reservation->check_in_date->format('Y-m-d'),
                    'check_out_date' => $reservation->check_out_date->format('Y-m-d'),
                    'hotel_id'       => $hotels->first()->id,
                    'room_type_id'   => $roomTypes->first()->id,
                    'room_id'        => $room->id,
                    'guest_name'     => $reservation->guest_name,
                    'guest_email'    => $reservation->guest_email,
                    'remarks'        => $reservation->remarks,
                ]))
                ->where('hotels', $expectedHotels)
                ->where('roomTypes', $expectedRoomTypes));
    }
}
