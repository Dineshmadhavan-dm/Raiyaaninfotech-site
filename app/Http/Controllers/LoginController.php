<?php

namespace App\Http\Controllers;


use App\Models\LoginActivity;
use App\Models\User;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LoginController extends Controller
{
    use LogActivity;
    public  function  loginget()
    {

        return view('auth.login');
    }


    public  function  fpwd()
    {
        return view('auth.forgetpwd');
    }










   public function loginPost(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember_me');

    if (Auth::attempt($credentials, $remember)) {
        $user = Auth::user();

        // Log successful login
        $this->logLoginActivity($user, 'login');

        // Remember me cookies
        if ($remember) {
            Cookie::queue('email_me', $request->email, 43200); // 30 days
            Cookie::queue('pwd', $request->password, 43200);
        } else {
            Cookie::queue(Cookie::forget('email_me'));
            Cookie::queue(Cookie::forget('pwd'));
        }

        // Redirect based on user category (1=Super Admin, 2=Employee, 3=Admin)
        if ($user->categorie == 2) {
            // Employee goes to emphome
            return redirect()->route('emphome')->with([
                'successempdhome' => 'Welcome back, ' . $user->name,
                'user_email' => $user->email
            ]);
        } else {
            // Super Admin (1) and Admin (3) go to dhome
            return redirect()->route('dhome')->with([
                'successdhome' => 'Welcome onboard, ' . $user->name,
                'user_email' => $user->email
            ]);
        }
    }

    // Log failed login attempt
    $this->logFailedLoginAttempt($request->email, $request->ip());

    return redirect()->route('loginget')
        ->withInput($request->only('email', 'remember_me'))
        ->with('error', 'Invalid credentials');
}



    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Log logout activity
            $this->logLoginActivity($user, 'logout');

            Auth::logout();
        }

        return redirect()->route('loginget')->with('info', 'Logout Successfully');
    }

    /**
     * Log failed login attempts (optional)
     */
    protected function logFailedLoginAttempt($email, $ip)
    {
        \Log::warning("Failed login attempt", [
            'email' => $email,
            'ip' => $ip,
            'time' => now(),
        ]);
    }


    public function activitystatus()
    {
        $query = LoginActivity::with('user')
            ->orderBy('created_at', 'desc');

        // If user is not super admin, only show their own activities
        if (!auth()->user()->hasRole('Super admin')) {
            $query->where('user_id', auth()->id());
        }

        $activities = $query->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user_id' => $activity->user_id,
                    'name' => optional($activity->user)->name ?? 'Deleted User',
                    'email' => optional($activity->user)->email ?? $activity->email,
                    'login_time' => $activity->login_time,
                    'logout_time' => $activity->logout_time,
                    'ip_address' => $activity->ip_address,
                    'location' => $activity->location,
                    'device_info' => $activity->device_info,
                    'activity_type' => $activity->activity_type,
                    'created_at' => $activity->created_at
                ];
            });

        return view('dashboard.netural.setting.activitylog', ['activities' => $activities]);
    }
}
