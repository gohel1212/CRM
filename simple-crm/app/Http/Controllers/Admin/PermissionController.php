<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::with('activities')->get();
        $permissions = $this->getAvailablePermissions();
        
        return view('admin.permissions.index', compact('users', 'permissions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Update user role and permissions
        $user->role = $validated['role'];
        $user->permissions = $validated['permissions'] ?? [];
        $user->save();

        Log::info('Admin updated user permissions', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'new_role' => $validated['role'],
            'permissions' => $validated['permissions']
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions updated successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'permissions' => 'array',
'role' => 'required|in:admin,user',
        ]);

        $users = User::whereIn('id', $validated['user_ids']);
        $users->update([
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? []
        ]);

        Log::info('Admin bulk updated permissions', [
            'admin_id' => auth()->id(),
            'user_count' => count($validated['user_ids']),
            'new_role' => $validated['role'],
            'permissions' => $validated['permissions']
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions updated for ' . count($validated['user_ids']) . ' users.');
    }

    private function getAvailablePermissions()
    {
        return [
            'customers' => [
                'label' => 'Customer Management',
                'permissions' => [
                    'customers.view' => 'View Customers',
                    'customers.create' => 'Create Customers',
                    'customers.edit' => 'Edit Customers',
                    'customers.delete' => 'Delete Customers',
                    'customers.export' => 'Export Customers',
                ]
            ],
            'deals' => [
                'label' => 'Deal Management',
                'permissions' => [
                    'deals.view' => 'View Deals',
                    'deals.create' => 'Create Deals',
                    'deals.edit' => 'Edit Deals',
                    'deals.delete' => 'Delete Deals',
                    'deals.export' => 'Export Deals',
                ]
            ],
            'pipeline' => [
                'label' => 'Pipeline Management',
                'permissions' => [
                    'pipeline.view' => 'View Pipeline',
                    'pipeline.manage' => 'Manage Pipeline',
                    'pipeline.stages' => 'Manage Stages',
                    'pipeline.reports' => 'View Reports',
                ]
            ],
            'contacts' => [
                'label' => 'Contact Management',
                'permissions' => [
                    'contacts.view' => 'View Contacts',
                    'contacts.create' => 'Create Contacts',
                    'contacts.edit' => 'Edit Contacts',
                    'contacts.delete' => 'Delete Contacts',
                    'contacts.export' => 'Export Contacts',
                ]
            ],
            'activities' => [
                'label' => 'Activity Management',
                'permissions' => [
                    'activities.view' => 'View Activities',
                    'activities.create' => 'Create Activities',
                    'activities.edit' => 'Edit Activities',
                    'activities.delete' => 'Delete Activities',
                ]
            ],
            'reports' => [
                'label' => 'Reports & Analytics',
                'permissions' => [
                    'reports.view' => 'View Reports',
                    'reports.export' => 'Export Reports',
                    'reports.analytics' => 'View Analytics',
                ]
            ],
            'admin' => [
                'label' => 'Administration',
                'permissions' => [
                    'admin.users' => 'Manage Users',
                    'admin.permissions' => 'Manage Permissions',
                    'admin.settings' => 'System Settings',
                    'admin.logs' => 'View System Logs',
                ]
            ]
        ];
    }
}
