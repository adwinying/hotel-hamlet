<?php

namespace Tests\Feature\Actions\User;

use App\Actions\User\UpdateUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testCanUpdateDb()
    {
        $user  = User::factory()->create();
        $input = ['name' => 'John Doe'];

        $update = app(UpdateUser::class);
        $result = $update($user, $input);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', $input);
    }

    public function testHashesPasswordBeforeUpdate()
    {
        $user  = User::factory()->create();
        $input = ['password' => 'secret'];

        $update = app(UpdateUser::class);
        $result = $update($user, $input);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', $input);
        $this->assertTrue(Hash::check(
            $input['password'],
            $user->fresh()->password,
        ));
    }
}
