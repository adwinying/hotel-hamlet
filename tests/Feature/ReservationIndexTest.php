<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReservationIndexTest extends TestCase
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
        ])->map->only('id', 'hotel_id', 'name');

        $room = Room::factory()->create([
            'room_type_id' => $roomTypes->random()['id'],
        ]);

        $reservations = Reservation::factory()->count(3)->create([
            'room_id' => $room->id,
        ])->map($this->formatReservationResult(...));

        $this->get('/admin/reservations')->assertInertia(fn (Assert $page) => $page
            ->component('Reservation/Index')
            ->has('query', fn (Assert $query) => $query
                ->has('hotel_id')
                ->has('room_type_id')
                ->has('check_in_date')
                ->has('check_out_date')
                ->has('guest_name')
                ->has('guest_email'))
            ->where('result.data', $reservations)
            ->where('hotels', $hotels)
            ->where('roomTypes', $roomTypes));
    }

    public function testCanFilterByHotelId(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?hotel_id={$roomType->hotel_id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->where('hotel_id', (string) $roomType->hotel_id)
                    ->has('room_type_id')
                    ->has('check_in_date')
                    ->has('check_out_date')
                    ->has('guest_name')
                    ->has('guest_email'))
                ->where('result.data', Reservation::filter([
                    'hotel_id' => $roomType->hotel_id,
                ])
                ->get()
                ->map($this->formatReservationResult(...))));
    }

    public function testCanFilterByRoomTypeId(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?room_type_id={$room->room_type_id}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->where('room_type_id', (string) $room->room_type_id)
                    ->has('check_in_date')
                    ->has('check_out_date')
                    ->has('guest_name')
                    ->has('guest_email'))
                ->where('result.data', Reservation::filter([
                    'room_id' => $reservation->room_id,
                ])
                ->get()
                ->map([$this, 'formatReservationResult'])));
    }

    public function testCanFilterByCheckInDate(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();
        $checkInDate  = Carbon::parse($reservation->check_in_date)
            ->format('Y-m-d');

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?check_in_date={$checkInDate}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->has('room_type_id')
                    ->where('check_in_date', $checkInDate)
                    ->has('check_out_date')
                    ->has('guest_name')
                    ->has('guest_email'))
                ->where('result.data', Reservation::filter([
                    'check_in_date' => $checkInDate,
                ])
                ->get()
                ->map([$this, 'formatReservationResult'])));
    }

    public function testCanFilterByCheckOutDate(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();
        $checkOutDate = Carbon::parse($reservation->check_out_date)
            ->format('Y-m-d');

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?check_out_date={$checkOutDate}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->has('room_type_id')
                    ->has('check_in_date')
                    ->where('check_out_date', $checkOutDate)
                    ->has('guest_name')
                    ->has('guest_email'))
                ->where('result.data', Reservation::filter([
                    'check_out_date' => $checkOutDate,
                ])
                ->get()
                ->map([$this, 'formatReservationResult'])));
    }

    public function testCanFilterByGuestName(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?guest_name={$reservation->guest_name}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->has('room_type_id')
                    ->has('check_in_date')
                    ->has('check_out_date')
                    ->where('guest_name', $reservation->guest_name)
                    ->has('guest_email'))
                ->where('result.data', Reservation::filter([
                    'guest_name' => $reservation->guest_name,
                ])
                ->get()
                ->map([$this, 'formatReservationResult'])));
    }

    public function testCanFilterByGuestEmail(): void
    {
        $reservations = Reservation::factory()->count(3)->create();
        $reservation  = $reservations->random();
        $guestEmail   = urlencode($reservation->guest_email);

        $room = Room::factory()->create([
            'id' => $reservation->room_id,
        ]);
        $roomType = RoomType::factory()->create([
            'id' => $room->room_type_id,
        ]);
        Hotel::factory()->create([
            'id' => $roomType->hotel_id,
        ]);

        $this->get("/admin/reservations?guest_email={$guestEmail}")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Reservation/Index')
                ->has('query', fn (Assert $query) => $query
                    ->has('hotel_id')
                    ->has('room_type_id')
                    ->has('check_in_date')
                    ->has('check_out_date')
                    ->has('guest_name')
                    ->where('guest_email', $reservation->guest_email))
                ->where('result.data', Reservation::filter([
                    'guest_email' => $reservation->guest_email,
                ])
                ->get()
                ->map([$this, 'formatReservationResult'])));
    }

    /**
     * @return array<string,mixed>
     */
    public function formatReservationResult(Reservation $reservation): array
    {
        return [
            'id'             => $reservation->id,
            'room_id'        => $reservation->room_id,
            'check_in_date'  => $reservation->check_in_date->format('Y-m-d'),
            'check_out_date' => $reservation->check_out_date->format('Y-m-d'),
            'guest_name'     => $reservation->guest_name,
            'guest_email'    => $reservation->guest_email,
            'room_no'        => $reservation->room->room_no,
            'room_type_name' => $reservation->room->roomType->name,
            'hotel_name'     => $reservation->room->roomType->hotel->name,
        ];
    }
}
