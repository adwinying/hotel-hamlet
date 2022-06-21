<?php

namespace Tests\Feature;

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

    public function testCanShowCreatePage()
    {
        $this->get('/admin/hotels/create')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Hotel/Form')
                ->missing('hotel'));
    }

    public function testCanCreateHotel()
    {
        $hotel = Hotel::factory()->make();
        $input = $hotel->only('name', 'is_hidden');

        $this->post('/admin/hotels', $input)
            ->assertRedirect()
            ->assertSessionHas('success', 'Hotel created.');

        $this->assertDatabaseHas('hotels', $input);
    }
}
