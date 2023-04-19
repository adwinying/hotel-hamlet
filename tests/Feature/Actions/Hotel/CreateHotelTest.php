<?php

namespace Tests\Feature\Actions\Hotel;

use App\Actions\Hotel\CreateHotel;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateHotelTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesOnlyOneHotel(): void
    {
        $input = Hotel::factory()->make()->toArray();

        $create = app(CreateHotel::class);
        $result = $create->execute($input);

        $this->assertEquals($input['name'], $result->name);
        $this->assertEquals($input['is_hidden'], $result->is_hidden);
        $this->assertDatabaseHas('hotels', [
            'name'      => $input['name'],
            'is_hidden' => $input['is_hidden'],
        ]);
    }
}
