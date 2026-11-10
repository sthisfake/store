<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{

    public function login(Request $request): RedirectResponse
    {
        $requestBody = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $isAuthenticated = Auth::attempt([
            'email' => $requestBody['email'],
            'password' => $requestBody['password'],
            'role' => 'admin',
            'is_active' => true,
        ], $request->boolean('remember'));

        if (! $isAuthenticated) {
            return back()
                ->withErrors(['email' => 'The credentials are incorrect.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.showLogin');
    }


    public function showLogin(): View
    {
        return view('admin.auth.login');
    }
}
