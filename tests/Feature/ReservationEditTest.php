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

class ReservationEditTest extends TestCase
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

    public function testCanUpdateRoomId(): void
    {
        $roomType = RoomType::factory()->hasRooms()->create();
        $room     = $roomType->rooms->first();

        $reservation   = Reservation::factory()->create();
        $reservationId = $reservation->id;

        $input = [
            'check_in_date'  => $reservation->check_in_date->format('Y-m-d'),
            'check_out_date' => $reservation->check_out_date->format('Y-m-d'),
            'room_id'        => $room->id,
            'guest_name'     => $reservation->guest_name,
            'guest_email'    => $reservation->guest_email,
            'remarks'        => $reservation->remarks,
        ];

        $this->from(route('reservations.show', [$reservation]))
            ->put("/admin/reservations/$reservationId", $input)
            ->assertRedirect(route('reservations.show', [$reservation]))
            ->assertSessionHas('success', 'Reservation updated.')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'id'      => $reservationId,
            'room_id' => $room->id,
        ]);
    }

    public function testCanUpdateOtherParams(): void
    {
        $roomType = RoomType::factory()->hasRooms()->create();
        $room     = $roomType->rooms->first();

        $reservation   = Reservation::factory()->create();
        $reservationId = $reservation->id;

        $input = [
            'check_in_date'  => today()->addMonth()->format('Y-m-d'),
            'check_out_date' => today()->addMonth()->addDay()->format('Y-m-d'),
            'room_id'        => $room->id,
            'guest_name'     => 'foobar',
            'guest_email'    => 'foo@bar.com',
            'remarks'        => 'foo bar',
        ];

        $this->from(route('reservations.show', [$reservation]))
            ->put("/admin/reservations/$reservationId", $input)
            ->assertRedirect(route('reservations.show', [$reservation]))
            ->assertSessionHas('success', 'Reservation updated.')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', $input);
    }

    public function testCanDelete(): void
    {
        $reservation   = Reservation::factory()->create();
        $reservationId = $reservation->id;

        $this->from(route('reservations.show', [$reservation]))
            ->delete("/admin/reservations/$reservationId")
            ->assertRedirect(route('reservations.index'))
            ->assertSessionHas('success', 'Reservation deleted.');

        $this->assertDatabaseMissing('reservations', [
            'id'         => $reservationId,
            'deleted_at' => null,
        ]);
    }
}
