<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    /**
     * Update user info
     *
     * @param User  $user  Target user
     * @param array $input Data to update
     */
    public function execute(User $user, array $input): bool
    {
        // Hash password before saving to DB
        if ($password = Arr::pull($input, 'password')) {
            $input['password'] = Hash::make($password);
        }

        return $user->update($input);
    }
}
