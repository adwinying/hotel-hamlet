<?php

namespace Tests\Feature;

use App\Exceptions\DataInvalidException;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HotelCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testCanShowCreatePage(): void
    {
        $this->get('/admin/hotels/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Hotel/Form')
                ->where('hotel', null));
    }

    public function testCanCreateHotel(): void
    {
        $hotel = Hotel::factory()->make();
        if (!($hotel instanceof Hotel)) {
            throw new DataInvalidException();
        }
        $input = $hotel->only('name', 'is_hidden');

        $this->post('/admin/hotels', $input)
            ->assertRedirect()
            ->assertSessionHas('success', 'Hotel created.');

        $this->assertDatabaseHas('hotels', $input);
    }
}
