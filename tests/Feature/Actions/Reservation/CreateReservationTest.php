<?php

namespace Tests\Feature\Actions\Reservation;

use App\Actions\Reservation\CreateReservation;
use App\Exceptions\RoomUnavailableException;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testThrowsExceptionWhenRoomTypeHasNoAvailableRooms(): void
    {
        $roomType = RoomType::factory()->create();

        $input = Reservation::factory()->make([
            'room_type_id' => $roomType->id,
        ])->toArray();

        $this->expectException(RoomUnavailableException::class);

        $create = app(CreateReservation::class);
        $create->execute($input);
    }

    public function testThrowsExceptionWhenRoomIsNotAvailable(): void
    {
        $roomType = RoomType::factory()->hasRooms(1)->create();

        Reservation::factory()->create([
            'room_id'        => $roomType->rooms->first()->id,
            'check_in_date'  => today()->addMonth(),
            'check_out_date' => today()->addMonth()->addDays(5),
        ]);

        $input = Reservation::factory()->make([
            'room_id'        => $roomType->rooms->first()->id,
            'check_in_date'  => today()->addMonth()->subDay(),
            'check_out_date' => today()->addMonth()->addDays(3),
        ])->toArray();

        $this->expectException(RoomUnavailableException::class);

        $create = app(CreateReservation::class);
        $create->execute($input);
    }

    public function testSetFirstAvailableRoomIfRoomTypeIdProvided(): void
    {
        $roomType = RoomType::factory()->hasRooms(1)->create();

        $input = Reservation::factory()->make([
            'room_id' => $roomType->rooms->first()->id,
        ])->toArray();

        $create = app(CreateReservation::class);
        $result = $create->execute($input);

        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'id'             => $result->id,
            'room_id'        => $input['room_id'],
            'check_in_date'  => $input['check_in_date'],
            'check_out_date' => $input['check_out_date'],
            'guest_name'     => $input['guest_name'],
            'guest_email'    => $input['guest_email'],
        ]);
    }

    public function testRandomFourDigitPinIsGenerated(): void
    {
        $roomType = RoomType::factory()->hasRooms(1)->create();

        $input = Reservation::factory()->make([
            'room_id' => $roomType->rooms->first()->id,
        ])->toArray();

        $create = app(CreateReservation::class);
        $result = $create->execute($input);

        $pin = $result->pin;
        $this->assertTrue($pin >= 0 && $pin <= 9999);
        $this->assertTrue(Hash::check($pin, Reservation::query()->first()->pin));
    }
}
