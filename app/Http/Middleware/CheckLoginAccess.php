<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->login_access === 0) {
            auth()->logout();
            return redirect()->route('loginget')->with('erroralert', 'Your account access has been disabled. Please contact admin.');
        }

        return $next($request);
    }
}
