<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\RoomTypeRequest;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class RoomTypeRequestTest extends ValidationTestCase
{
    use RefreshDatabase;

    protected $hotelId      = 99;
    protected $roomTypeName = 'foobar';

    protected function setUp(): void
    {
        parent::setUp();

        Hotel::factory()->create([
            'id' => $this->hotelId,
        ]);

        RoomType::factory()->create([
            'hotel_id' => $this->hotelId,
            'name'     => $this->roomTypeName,
        ]);
    }

    protected function request(): FormRequest
    {
        return new RoomTypeRequest();
    }

    protected function baseInput(): array
    {
        return [
            'hotel_id' => $this->hotelId,
            'name'     => Str::random(255),
            'price'    => mt_rand(0, 99999),
        ];
    }

    public function formData()
    {
        return [
            'All OK' => [
                true,
            ],

            'hotel_id is empty' => [
                false, [], 'hotel_id',
            ],

            'hotel_id does not exist in DB' => [
                false, ['hotel_id' => $this->hotelId + 1],
            ],

            'name is empty' => [
                false, [], 'name',
            ],

            'name exceeds 255 chars' => [
                false, ['name' => Str::random(256)],
            ],

            'name + hotel_id combo is not unique' => [
                false, ['name' => $this->roomTypeName],
            ],

            'price is empty' => [
                false, [], 'price',
            ],

            'price is not integer' => [
                false, ['price' => 123.45],
            ],

            'price exceeds max value' => [
                false, ['price' => 100000],
            ],
        ];
    }
}
