<?php

namespace Tests\Feature\Actions\Hotel;

use App\Actions\Hotel\UpdateHotel;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateHotelTest extends TestCase
{
    use RefreshDatabase;

    public function testCanUpdateDb()
    {
        $hotel = Hotel::factory()->create();
        $input = [
            'name'      => 'foobar',
            'is_hidden' => mt_rand(0, 1),
        ];

        $update = app(UpdateHotel::class);
        $result = $update->execute($hotel, $input);

        $this->assertEquals($hotel->id, $result->id);
        $this->assertDatabaseHas('hotels', [
            'id'        => $hotel->id,
            'name'      => $input['name'],
            'is_hidden' => $input['is_hidden'],
        ]);
    }
}
