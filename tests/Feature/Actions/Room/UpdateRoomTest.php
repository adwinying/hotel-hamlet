<?php

namespace Tests\Feature\Actions\Room;

use App\Actions\Room\UpdateRoom;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateRoomTest extends TestCase
{
    use RefreshDatabase;

    public function testCanUpdateDb()
    {
        $room  = Room::factory()->create();
        $input = [
            'room_type_id' => mt_rand(1, 99),
            'room_no'      => '555',
        ];

        $update = app(UpdateRoom::class);
        $result = $update->execute($room, $input);

        $this->assertEquals($room->id, $result->id);
        $this->assertDatabaseHas('rooms', [
            'id'           => $room->id,
            'room_type_id' => $input['room_type_id'],
            'room_no'      => $input['room_no'],
        ]);
    }
}
