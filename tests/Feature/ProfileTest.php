<?php

namespace Tests\Feature;

use App\Models\User;
use Inertia\Testing\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testCanShowProfilePage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/admin/profile')->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Index')
            ->has('profile')
            ->whereAll([
                'profile.name'  => $user->name,
                'profile.email' => $user->email,
            ])
        );
    }
}
