<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdvancedUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['activities']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20);

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'recent_signups' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users.advanced', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['activities' => function($query) {
            $query->latest()->limit(10);
        }]);

        $userStats = [
            'total_activities' => $user->activities()->count(),
            'recent_activities' => $user->activities()->where('created_at', '>=', now()->subDays(7))->count(),
            'last_login' => $user->updated_at,
        ];

        return view('admin.users.show', compact('user', 'userStats'));
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
            'is_active' => 'boolean',
            'permissions' => 'array',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
            'permissions' => $validated['permissions'] ?? [],
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'timezone' => $validated['timezone'] ?? 'UTC',
        ]);

        Log::info('Admin created new user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'role' => $user->role
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
            'is_active' => 'boolean',
            'permissions' => 'array',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $validated['is_active'] ?? true;
        $user->permissions = $validated['permissions'] ?? [];
        $user->department = $validated['department'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->timezone = $validated['timezone'] ?? 'UTC';

        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Log::info('Admin updated user', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'changes' => $validated
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

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete,change_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
'new_role' => 'required_if:action,change_role|in:admin,user',
        ]);

        $users = User::whereIn('id', $validated['user_ids']);

        switch ($validated['action']) {
            case 'activate':
                $users->update(['is_active' => true]);
                $message = 'Users activated successfully.';
                break;
            case 'deactivate':
                $users->update(['is_active' => false]);
                $message = 'Users deactivated successfully.';
                break;
            case 'delete':
                $users->where('id', '!=', auth()->id())->delete();
                $message = 'Users deleted successfully.';
                break;
            case 'change_role':
                $users->update(['role' => $validated['new_role']]);
                $message = 'User roles updated successfully.';
                break;
        }

        Log::info('Admin bulk action performed', [
            'admin_id' => auth()->id(),
            'action' => $validated['action'],
            'user_count' => count($validated['user_ids'])
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    public function export(Request $request)
    {
        $users = User::with('activities')->get();

        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Status', 'Department', 
                'Phone', 'Timezone', 'Total Activities', 'Last Login', 'Created At'
            ]);

            // CSV Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->department ?? 'N/A',
                    $user->phone ?? 'N/A',
                    $user->timezone ?? 'UTC',
                    $user->activities->count(),
                    $user->updated_at->format('Y-m-d H:i:s'),
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
