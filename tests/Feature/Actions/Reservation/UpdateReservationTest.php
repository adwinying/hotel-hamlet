<?php

namespace Tests\Feature\Actions\Reservation;

use App\Actions\Reservation\UpdateReservation;
use App\Actions\Room\GetAvailableRooms;
use App\Exceptions\RoomUnavailableException;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testSkipAvailabilityCheckIfRoomIdAndDatesUnchanged(): void
    {
        $reservation = Reservation::factory()->create();

        $getAvailableRooms = $this
            ->createPartialMock(GetAvailableRooms::class, ['execute']);
        $getAvailableRooms->expects($this->never())->method('execute');
        $this->instance(GetAvailableRooms::class, $getAvailableRooms);

        $input = [];

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);
    }

    public function testCheckAvailabilityWhenRoomIdChanged(): void
    {
        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->create();

        $getAvailableRooms = $this
            ->createPartialMock(GetAvailableRooms::class, ['execute']);
        $getAvailableRooms->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(fn ($type) => $type->id === $roomType->id),
                $reservation->check_in_date,
                $reservation->check_out_date,
                $reservation,
            )
            ->willReturn(Room::query()->limit(0)->get());
        $this->instance(GetAvailableRooms::class, $getAvailableRooms);

        $input = ['room_id' => $room->id];

        $this->expectException(RoomUnavailableException::class);

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);
    }

    public function testCheckAvailabilityWhenCheckInDateChanged(): void
    {
        $input = ['check_in_date' => today()->format('Y-m-d')];

        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->for($room)->create();

        $getAvailableRooms = $this
            ->createPartialMock(GetAvailableRooms::class, ['execute']);
        $getAvailableRooms->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(fn ($type) => $type->id === $roomType->id),
                $input['check_in_date'],
                $reservation->check_out_date,
                $reservation,
            )
            ->willReturn(Room::limit(0)->get());
        $this->instance(GetAvailableRooms::class, $getAvailableRooms);

        $this->expectException(RoomUnavailableException::class);

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);
    }

    public function testCheckAvailabilityWhenCheckOutDateChanged(): void
    {
        $input = ['check_out_date' => today()->format('Y-m-d')];

        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->for($room)->create();

        $getAvailableRooms = $this
            ->createPartialMock(GetAvailableRooms::class, ['execute']);
        $getAvailableRooms->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(fn ($type) => $type->id === $roomType->id),
                $reservation->check_in_date,
                $input['check_out_date'],
                $reservation,
            )
            ->willReturn(Room::limit(0)->get());
        $this->instance(GetAvailableRooms::class, $getAvailableRooms);

        $this->expectException(RoomUnavailableException::class);

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);
    }

    public function testCanUpdateRoomId(): void
    {
        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->create();

        $input = ['room_id' => $room->id];

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);

        $this->assertDatabaseHas('reservations', [
            'id'      => $reservation->id,
            'room_id' => $input['room_id'],
        ]);
    }

    public function testCanUpdateCheckInDate(): void
    {
        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->for($room)->create();

        $input = ['check_in_date' => today()->format('Y-m-d')];

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);

        $this->assertDatabaseHas('reservations', [
            'id'            => $reservation->id,
            'check_in_date' => $input['check_in_date'],
        ]);
    }

    public function testCanUpdateCheckOutDate(): void
    {
        $roomType    = RoomType::factory()->hasRooms()->create();
        $room        = $roomType->rooms->first();
        $reservation = Reservation::factory()->for($room)->create();

        $input = ['check_out_date' => today()->format('Y-m-d')];

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);

        $this->assertDatabaseHas('reservations', [
            'id'             => $reservation->id,
            'check_out_date' => $input['check_out_date'],
        ]);
    }

    public function testCanUpdateOtherFields(): void
    {
        $reservation = Reservation::factory()->create();

        $input = [
            'guest_name'  => 'foobar',
            'guest_email' => 'foo@bar.com',
            'remarks'     => 'foo bar',
        ];

        $update = app(UpdateReservation::class);
        $update->execute($reservation, $input);

        $this->assertDatabaseHas('reservations', array_merge($input, [
            'id' => $reservation->id,
        ]));
    }
}
