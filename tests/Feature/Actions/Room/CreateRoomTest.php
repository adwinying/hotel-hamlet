<?php

namespace Tests\Feature\Actions\Room;

use App\Actions\Room\CreateRoom;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRoomTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesOnlyOneRoom(): void
    {
        $input = Room::factory()->make()->toArray();

        $create = app(CreateRoom::class);
        $result = $create->execute($input);

        $this->assertEquals($input['room_type_id'], $result->room_type_id);
        $this->assertEquals($input['room_no'], $result->room_no);
        $this->assertDatabaseHas('rooms', [
            'room_type_id' => $input['room_type_id'],
            'room_no'      => $input['room_no'],
        ]);
    }
}
