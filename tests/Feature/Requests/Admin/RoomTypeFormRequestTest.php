<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\RoomTypeFormRequest;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class RoomTypeFormRequestTest extends ValidationTestCase
{
    use RefreshDatabase;

    protected static int $hotelId = 99;

    protected static string $roomTypeName = 'foobar';

    protected function setUp(): void
    {
        parent::setUp();

        Hotel::factory()->create([
            'id' => self::$hotelId,
        ]);

        RoomType::factory()->create([
            'hotel_id' => self::$hotelId,
            'name'     => self::$roomTypeName,
        ]);
    }

    protected function request(): FormRequest|string
    {
        return RoomTypeFormRequest::class;
    }

    protected function baseInput(): array
    {
        return [
            'hotel_id' => self::$hotelId,
            'name'     => Str::random(255),
            'price'    => mt_rand(0, 99999),
        ];
    }

    public static function formData(): array
    {
        return [
            'All OK' => [
                true,
            ],

            'hotel_id is empty' => [
                false, [], 'hotel_id',
            ],

            'hotel_id does not exist in DB' => [
                false, ['hotel_id' => self::$hotelId + 1],
            ],

            'name is empty' => [
                false, [], 'name',
            ],

            'name exceeds 255 chars' => [
                false, ['name' => Str::random(256)],
            ],

            'name + hotel_id combo is not unique' => [
                false, ['name' => self::$roomTypeName],
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
