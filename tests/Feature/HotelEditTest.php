<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class HotelEditTest extends TestCase
{
    use RefreshDatabase;

    public function testCanShowProfilePage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotel   = Hotel::factory()->create();
        $hotelId = $hotel->id;

        $this->get("/admin/hotels/$hotelId")
            ->assertInertia(fn (Assert $page) => $page
                ->component('Hotel/Form')
                ->has('hotel', fn (Assert $page) => $page
                    ->where('name', $hotel->name)
                    ->where('is_hidden', $hotel->is_hidden)));
    }
}
