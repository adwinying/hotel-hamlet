<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
