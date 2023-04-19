<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\HotelRequest;
use App\Models\Hotel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class HotelRequestTest extends ValidationTestCase
{
    use RefreshDatabase;

    protected static string $hotelName = 'foobar';

    protected function setUp(): void
    {
        parent::setUp();

        Hotel::factory()->create([
            'name' => self::$hotelName,
        ]);
    }

    protected function request(): FormRequest
    {
        return new HotelRequest();
    }

    protected function baseInput(): array
    {
        return [
            'name'      => Str::random(255),
            'is_hidden' => mt_rand(0, 1),
        ];
    }

    public static function formData(): array
    {
        return [
            'All OK' => [
                true,
            ],

            'name is empty' => [
                false, [], 'name',
            ],

            'name exceeds 255 chars' => [
                false, ['name' => Str::random(256)],
            ],

            'name is not unique' => [
                false, ['name' => self::$hotelName],
            ],

            'is_hidden is empty' => [
                false, [], 'is_hidden',
            ],

            'is_hidden is not boolean' => [
                false, ['is_hidden' => 'foobar'],
            ],
        ];
    }
}
