<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class ProfileRequestTest extends ValidationTestCase
{
    protected $oldPassword = 'password';

    protected $newPassword = 'password2';

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->make([
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $this->actingAs($user);
    }

    protected function request(): FormRequest
    {
        return new ProfileRequest();
    }

    protected function baseInput(): array
    {
        return [
            'name'                  => Str::random(255),
            'email'                 => Str::random(243) . '@example.org',
            'old_password'          => $this->oldPassword,
            'password'              => $this->newPassword,
            'password_confirmation' => $this->newPassword,
        ];
    }

    public static function formData()
    {
        return [
            'All OK' => [
                true,
            ],

            'name is empty' => [
                false, [], 'name',
            ],

            'name exceeds 255 chars' => [
                false, ['name' => Str::random(256)],
            ],

            'email is empty' => [
                false, [], 'email',
            ],

            'email is not valid email format' => [
                false, ['email' => 'foobar'],
            ],

            'email exceeds 255 chars' => [
                false, ['email' => Str::random(244) . '@example.org'],
            ],

            'old_password is empty' => [
                false, [], 'old_password',
            ],

            'old_password is empty when password is empty' => [
                true, ['password' => null], 'old_password',
            ],

            'old_password is incorrect' => [
                false, ['password' => 'foobar'],
            ],

            'password is empty' => [
                true, [], 'password',
            ],

            'password is less than 8 chars' => [
                false, [
                    'password'              => 'foobar',
                    'password_confirmation' => 'foobar',
                ],
            ],

            'password_confirmation is empty' => [
                false, [], 'password_confirmation',
            ],

            'password_confirmation does not match password' => [
                false, ['password_confirmation' => 'foobar'],
            ],
        ];
    }
}
