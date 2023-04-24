<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileIndexRequest;
use App\Http\Responses\Admin\ProfileIndexResponse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(): Response
    {
        /** @var User */
        $user = auth()->user();

        return Inertia::render('Profile/Index', ProfileIndexResponse::from([
            'profile' => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]));
    }

    public function update(
        ProfileIndexRequest $request,
        UpdateUser $update,
    ): RedirectResponse {
        /** @var User */
        $user  = auth()->user();
        $input = $request->except('old_password')->toArray();

        $update->execute($user, $input);

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
