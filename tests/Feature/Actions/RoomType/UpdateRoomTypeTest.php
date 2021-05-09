<?php

namespace Tests\Feature\Actions\RoomType;

use App\Actions\RoomType\UpdateRoomType;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateRoomTypeTest extends TestCase
{
    use RefreshDatabase;

    public function testCanUpdateDb()
    {
        $roomType = RoomType::factory()->create();
        $input    = [
            'hotel_id' => mt_rand(1, 99),
            'name'     => 'foobar',
        ];

        $update = app(UpdateRoomType::class);
        $result = $update->execute($roomType, $input);

        $this->assertEquals($roomType->id, $result->id);
        $this->assertDatabaseHas('room_types', [
            'id'       => $roomType->id,
            'hotel_id' => $input['hotel_id'],
            'name'     => $input['name'],
        ]);
    }
}
