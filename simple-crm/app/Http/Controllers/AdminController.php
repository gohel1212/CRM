<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level in routes/admin.php
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'recent_logs' => $this->getRecentLogs(10)
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::with('activities')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        Log::info('Admin created new user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $validated['is_active'] ?? true;

        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Log::info('Admin updated user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        Log::info('Admin deleted user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function logs()
    {
        $logs = $this->getSystemLogs();
        return view('admin.logs', compact('logs'));
    }

    public function permissions()
    {
        $users = User::with('activities')->get();
        return view('admin.permissions', compact('users'));
    }

    public function updatePermissions(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array'
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Update user permissions (you can extend this based on your needs)
        $user->permissions = json_encode($validated['permissions'] ?? []);
        $user->save();

        Log::info('Admin updated user permissions', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'permissions' => $validated['permissions']
        ]);

        return redirect()->route('admin.permissions')
            ->with('success', 'Permissions updated successfully.');
    }

    private function getRecentLogs($limit = 10)
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                return [];
            }

            $logs = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -$limit);

            foreach ($recentLines as $line) {
                if (preg_match('/\[(.*?)\].*?(\w+):\s*(.*)/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'message' => trim($matches[3])
                    ];
                }
            }

            return array_reverse($logs);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSystemLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                return [];
            }

            $logs = [];
            $lines = file($logFile);
            $recentLines = array_slice($lines, -100); // Get last 100 lines

            foreach ($recentLines as $line) {
                if (preg_match('/\[(.*?)\].*?(\w+):\s*(.*)/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'message' => trim($matches[3])
                    ];
                }
            }

            return array_reverse($logs);
        } catch (\Exception $e) {
            return [];
        }
    }
}
