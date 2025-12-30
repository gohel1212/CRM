<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{

    public function index()
    {
        $users = User::with('activities')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        Log::info('Admin deleted user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show advanced user management
     */
    public function advanced()
    {
        $users = User::with('activities')->paginate(15);
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'regular_users' => User::where('role', 'user')->count(),
            'recent_signups' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users.advanced', compact('users', 'stats'));
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        $users = User::with('activities')->get();

        $filename = 'users_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Role',
                'Status',
                'Created At',
                'Last Login',
                'Total Activities',
            ]);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                    $user->activities->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Handle bulk user actions
     */
    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;
        $count = 0;

        switch ($action) {
            case 'activate':
                $count = User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = "Activated {$count} users successfully.";
                break;
            
            case 'deactivate':
                $count = User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = "Deactivated {$count} users successfully.";
                break;
            
            case 'delete':
                $count = User::whereIn('id', $userIds)->delete();
                $message = "Deleted {$count} users successfully.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show user details
     */
    public function details(User $user)
    {
        $user->load(['activities' => function($query) {
            $query->latest()->take(10);
        }]);

        $stats = [
            'total_activities' => $user->activities()->count(),
            'recent_activities' => $user->activities()->where('created_at', '>=', now()->subDays(7))->count(),
            'last_login' => $user->last_login_at,
            'account_age' => $user->created_at->diffInDays(now()),
        ];

        return view('admin.users.details', compact('user', 'stats'));
    }
}
