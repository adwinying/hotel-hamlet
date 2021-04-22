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

    public function testCanFilterByName()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name', 'is_hidden');

        $hotel = $hotels->random();

        $this->get("/admin/hotels?name={$hotel['name']}")
             ->assertInertia(
                 fn (Assert $page) => $page
                    ->component('Hotel/Index')
                    ->has('query', fn (Assert $query) => $query
                        ->has('name')
                        ->has('is_hidden'))
                        ->where('result.data', [$hotel])
             );
    }

    public function testCanFilterByIsHidden()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name', 'is_hidden');

        $isHidden = boolval(mt_rand(0, 1));
        $hotel    = $hotels->where('is_hidden', $isHidden);

        $this->get("/admin/hotels?is_hidden=$isHidden")
             ->assertInertia(
                 fn (Assert $page) => $page
                    ->component('Hotel/Index')
                    ->has('query', fn (Assert $query) => $query
                        ->has('name')
                        ->has('is_hidden'))
                        ->where('result.data', $hotel->toArray())
             );
    }

    public function testCannotFilterById()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotels = Hotel::factory()->count(3)->create()
            ->map
            ->only('id', 'name', 'is_hidden');

        $hotel = $hotels->random();

        $this->get("/admin/hotels?id={$hotel['id']}")
             ->assertInertia(
                 fn (Assert $page) => $page
                    ->component('Hotel/Index')
                    ->has('query', fn (Assert $query) => $query
                        ->has('name')
                        ->has('is_hidden'))
                        ->where('result.data', $hotels)
             );
    }
}
