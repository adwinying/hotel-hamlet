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
                    ->where('id', $hotel->id)
                    ->where('name', $hotel->name)
                    ->where('is_hidden', $hotel->is_hidden)));
    }

    public function testCanUpdateHotelName()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotel   = Hotel::factory()->create();
        $hotelId = $hotel->id;

        $input = [
            'name'      => 'new name',
            'is_hidden' => $hotel->is_hidden,
        ];

        $this->from("/admin/hotels/$hotelId")
            ->put("/admin/hotels/$hotelId", $input)
            ->assertRedirect("/admin/hotels/$hotelId")
            ->assertSessionHas('success', 'Hotel updated.');

        $this->assertDatabaseHas('hotels', [
            'id'   => $hotelId,
            'name' => $input['name'],
        ]);
    }

    public function testCanUpdateIsHiddenFlag()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $hotel   = Hotel::factory()->create();
        $hotelId = $hotel->id;

        $input = [
            'name'      => $hotel->name,
            'is_hidden' => !$hotel->is_hidden,
        ];

        $this->from("/admin/hotels/$hotelId")
            ->put("/admin/hotels/$hotelId", $input)
            ->assertRedirect("/admin/hotels/$hotelId")
            ->assertSessionHas('success', 'Hotel updated.');

        $this->assertDatabaseHas('hotels', [
            'id'        => $hotelId,
            'is_hidden' => $input['is_hidden'],
        ]);
    }
}
