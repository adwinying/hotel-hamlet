<?php

namespace Tests\Feature\Actions\Room;

use App\Actions\Room\DeleteRoom;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRoomTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeleteDb()
    {
        $room = Room::factory()->create();

        $delete = app(DeleteRoom::class);
        $result = $delete->execute($room);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('rooms', [
            'id'         => $room->id,
            'deleted_at' => null,
        ]);
    }
}
