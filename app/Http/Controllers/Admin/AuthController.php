<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function redirectToLogin()
    {
        return Inertia::location(route('login'));
    }

    public function show()
    {
        return view('admin.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }
}
