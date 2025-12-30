<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuditLogger;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log successful login
            AuditLogger::logLogin(Auth::user());

            return redirect()->intended('dashboard');
        }

        // Log failed login attempt
        AuditLogger::logFailedLogin($request->input('email'));

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout before actually logging out
        if ($user) {
            AuditLogger::logLogout($user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Log successful registration and auto-login
        AuditLogger::logLogin($user);

        return redirect()->route('dashboard');
    }

    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        // Log successful registration
        AuditLogger::logLogin($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            // Log successful login
            AuditLogger::logLogin($user);

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Log failed login attempt
        AuditLogger::logFailedLogin($request->input('email'));

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function apiLogout(Request $request)
    {
        $user = $request->user();

        // Log logout
        if ($user) {
            AuditLogger::logLogout($user);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}