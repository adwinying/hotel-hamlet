<?php

namespace Tests\Feature\Actions\RoomType;

use App\Actions\RoomType\CreateRoomType;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRoomTypeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesOnlyOneRoomType(): void
    {
        $input = RoomType::factory()->make()->toArray();

        $create = app(CreateRoomType::class);
        $result = $create->execute($input);

        $this->assertEquals($input['hotel_id'], $result->hotel_id);
        $this->assertEquals($input['name'], $result->name);
        $this->assertDatabaseHas('room_types', [
            'hotel_id' => $input['hotel_id'],
            'name'     => $input['name'],
        ]);
    }
}
