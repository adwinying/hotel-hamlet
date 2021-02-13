<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
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
        $this->get('/admin/login')->assertInertia(function ($page) {
            $page->component('Auth/Login');
        });
    }
}
