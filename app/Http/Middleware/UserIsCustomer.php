<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserIsCustomer
{
    public function handle(Request $request,Closure $next): Response {

        if ($request->user()->role !== 'customer') {
            abort(403);
        }

        if (! $request->user()->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
        }

        return $next($request);
    }
}