<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        return Inertia::render('Profile/Index', [
            'profile' => [
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ]);
    }

    public function update(ProfileRequest $request, UpdateUser $update)
    {
        $user  = auth()->user();
        $input = $request->only([
            'name',
            'email',
            'password',
        ]);

        $update($user, $input);

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
