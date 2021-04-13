<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class HotelIndexTest extends TestCase
{
    use RefreshDatabase;

    public function testCanShowHotelIndexPage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name', 'is_hidden');

        $this->get('/admin/hotels')->assertInertia(fn (Assert $page) => $page
            ->component('Hotel/Index')
            ->has('query', fn (Assert $query) => $query
                ->has('name')
                ->has('is_hidden'))
            ->where('result.data', $hotels));
    }
}
