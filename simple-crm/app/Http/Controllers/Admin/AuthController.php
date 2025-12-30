<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuditLogger;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->isAdmin() && Hash::check($credentials['password'], $user->password)) {
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            // Log successful admin login
            AuditLogger::logLogin($user);
            
            return redirect()->intended(route('admin.dashboard'));
        }

        // Log failed admin login attempt
        AuditLogger::logFailedLogin($credentials['email']);

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log admin logout before ending session
        if (Auth::guard('web')->user()) {
            AuditLogger::logLogout(Auth::guard('web')->user());
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
