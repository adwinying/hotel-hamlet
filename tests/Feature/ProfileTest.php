<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\Assert;
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
            ]));
    }

    public function testCanUpdateName()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $input = [
            'name'  => 'John Doe',
            'email' => $user->email,
        ];

        $this->from('/admin/profile')
            ->post('/admin/profile', $input)
            ->assertRedirect('/admin/profile')
            ->assertSessionHas('success', 'Profile updated.');
        $this->assertDatabaseHas('users', $input);
    }

    public function testCanUpdateEmail()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $input = [
            'name'  => $user->name,
            'email' => 'admin@example.org',
        ];

        $this->from('/admin/profile')
            ->post('/admin/profile', $input)
            ->assertRedirect('/admin/profile')
            ->assertSessionHas('success', 'Profile updated.');
        $this->assertDatabaseHas('users', $input);
    }

    public function testCanUpdatePassword()
    {
        $oldPassword = 'password';
        $newPassword = 'password2';

        $user = User::factory()->create([
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        $this->actingAs($user);

        $input = [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'old_password'          => $oldPassword,
            'password'              => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        $this->from('/admin/profile')
            ->post('/admin/profile', $input)
            ->assertRedirect('/admin/profile')
            ->assertSessionHas('success', 'Profile updated.');
        $this->assertTrue(Hash::check(
            $newPassword,
            $user->fresh()->password,
        ));
    }
}
