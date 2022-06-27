<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Profile/Index', [
            'profile' => [
                'name'  => auth()->user()?->name,
                'email' => auth()->user()?->email,
            ],
        ]);
    }

    public function update(
        ProfileRequest $request,
        UpdateUser $update,
    ): RedirectResponse {
        /** @var User */
        $user  = auth()->user();
        $input = $request->only([
            'name',
            'email',
            'password',
        ]);

        $update->execute($user, $input);

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
