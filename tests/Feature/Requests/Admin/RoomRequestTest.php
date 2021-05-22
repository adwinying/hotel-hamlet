<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\RoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class RoomRequestTest extends ValidationTestCase
{
    use RefreshDatabase;

    protected $roomTypeId = 99;
    protected $roomNo     = '101';

    protected function setUp(): void
    {
        parent::setUp();

        RoomType::factory()->create([
            'id' => $this->roomTypeId,
        ]);

        Room::factory()->create([
            'room_type_id' => $this->roomTypeId,
            'room_no'      => '101',
        ]);
    }

    protected function request(): FormRequest
    {
        return new RoomRequest();
    }

    protected function baseInput(): array
    {
        return [
            'room_type_id' => $this->roomTypeId,
            'room_no'      => Str::random(255),
        ];
    }

    public function formData()
    {
        return [
            'All OK' => [
                true,
            ],

            'room_type_id is empty' => [
                false, [], 'room_type_id',
            ],

            'room_type_id does not exist in DB' => [
                false, ['room_type_id' => $this->roomTypeId + 1],
            ],

            'room_no is empty' => [
                false, [], 'room_no',
            ],

            'room_no exceeds 255 chars' => [
                false, ['room_no' => Str::random(256)],
            ],

            'room_no + room_type_id combo is not unique' => [
                false, ['room_no' => $this->roomNo],
            ],
        ];
    }
}
