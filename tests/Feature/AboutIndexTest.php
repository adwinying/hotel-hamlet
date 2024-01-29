<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutIndexTest extends TestCase
{
    public function testCanShowPage(): void
    {
        $this->get('/about')
            ->assertStatus(200)
            ->assertSee('About Us');
    }
}
