<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ProvidersRouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAccessController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = ProvidersRouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        // Add login_access check to the credentials
        $credentials['login_access'] = 1;
        $credentials['delete_status'] = true;

        return $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            if ($user->login_access === 0) {
                return back()->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'emailalert' => 'Your account access is disabled. Please contact admin.',
                    ]);
            }

            if ($user->delete_status === false) {
                return back()->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'emailalert' => 'Your account has been deleted. Please contact admin.',
                    ]);
            }
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => __('auth.failed'),
            ]);
    }
}
