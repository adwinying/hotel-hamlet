<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRedirectToLoginIfUnauthenticated()
    {
        $this->assertGuest();

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function testRedirectToHomeIfAuthenticated()
    {
        $user = User::factory()->make();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $this->get('/admin/login')->assertRedirect('/admin');
    }

    public function testShowsLoginPage()
    {
        $this->get('/admin/login')->assertViewIs('admin.login');
    }

    public function testAbleToLoginWithValidCredentials()
    {
        $user = User::factory()->create();

        $data = [
            'email'    => $user->email,
            'password' => 'password',
            'remember' => true,
        ];

        $this->post('admin/login', $data)
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function testUnableToLoginWithInvalidCredentials()
    {
        $data = [
            'email'    => 'dummy@example.com',
            'password' => 'password',
            'remember' => true,
        ];

        $this->post('admin/login', $data)->assertRedirect();

        $this->assertGuest();
    }
}
