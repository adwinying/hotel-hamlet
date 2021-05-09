<?php

namespace Tests\Feature\Actions\RoomType;

use App\Actions\RoomType\DeleteRoomType;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRoomTypeTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeleteDb()
    {
        $roomType = RoomType::factory()->create();

        $delete = app(DeleteRoomType::class);
        $result = $delete->execute($roomType);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('room_types', [
            'id'         => $roomType->id,
            'deleted_at' => null,
        ]);
    }
}
