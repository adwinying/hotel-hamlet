<?php

namespace Tests\Feature\Actions\Hotel;

use App\Actions\Hotel\DeleteHotel;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteHotelTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeleteDb()
    {
        $hotel = Hotel::factory()->create();

        $delete = app(DeleteHotel::class);
        $result = $delete->execute($hotel);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('hotels', [
            'id'         => $hotel->id,
            'deleted_at' => null,
        ]);
    }
}
