@extends('admin.layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Permissions Management</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage user roles and permissions across the system.</p>
        </div>
    </div>

    <!-- Users and Permissions Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">User Permissions</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click on permissions to toggle them for each user.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Role
                        </th>
                        @foreach($permissions as $module => $modulePermissions)
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ $modulePermissions['label'] }}
                            </th>
                        @endforeach
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.permissions.update') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <select name="role" onchange="this.form.submit()" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </form>
                            </td>
                            
                            @foreach($permissions as $module => $modulePermissions)
                                <td class="px-6 py-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($modulePermissions['permissions'] as $permission => $label)
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       id="permission_{{ $user->id }}_{{ $permission }}"
                                                       name="permissions[]" 
                                                       value="{{ $permission }}"
                                                       {{ $user->hasPermission($permission) ? 'checked' : '' }}
                                                       onchange="updateUserPermission({{ $user->id }}, '{{ $permission }}', this.checked)"
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                                                <label for="permission_{{ $user->id }}_{{ $permission }}" class="ml-2 text-xs text-gray-700 dark:text-gray-300">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button onclick="updateAllPermissions({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Update All
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bulk Permission Update -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bulk Permission Update</h3>
        <form method="POST" action="{{ route('admin.permissions.bulk') }}" id="bulk-permission-form">
            @csrf
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="user_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Users</label>
                    <select name="user_ids[]" id="user_ids" multiple class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="bulk_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Set Role</label>
                    <select name="role" id="bulk_role" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Set Permissions</label>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">{{ $modulePermissions['label'] }}</h4>
                            <div class="space-y-2">
                                @foreach($modulePermissions['permissions'] as $permission => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission }}"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                                        <label class="ml-2 text-xs text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Selected Users
                </button>
            </div>
        </form>
    </div>

    <!-- Permission Presets -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Permission Presets</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Admin</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Full access to all features and system administration.</p>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• All customer permissions</li>
                    <li>• All deal permissions</li>
                    <li>• All pipeline permissions</li>
                    <li>• All contact permissions</li>
                    <li>• All activity permissions</li>
                    <li>• All report permissions</li>
                    <li>• All admin permissions</li>
                </ul>
            </div>
            
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">User</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Standard user access with basic CRM functionality.</p>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• View customers</li>
                    <li>• Create/edit own deals</li>
                    <li>• View pipeline</li>
                    <li>• View contacts</li>
                    <li>• Create activities</li>
                    <li>• View basic reports</li>
                    <li>• No admin permissions</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function updateUserPermission(userId, permission, isChecked) {
    // This would typically make an AJAX call to update the permission
    // For now, we'll just show a visual feedback
    console.log(`Updating user ${userId} permission ${permission} to ${isChecked}`);
}

function updateAllPermissions(userId) {
    // Collect all checked permissions for this user
    const checkboxes = document.querySelectorAll(`input[name="permissions[]"][id*="permission_${userId}_"]`);
    const permissions = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    
    // Submit form to update all permissions for this user
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.permissions.update") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const userIdInput = document.createElement('input');
    userIdInput.type = 'hidden';
    userIdInput.name = 'user_id';
    userIdInput.value = userId;
    form.appendChild(userIdInput);
    
    permissions.forEach(permission => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'permissions[]';
        input.value = permission;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Initialize multi-select for user selection
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_ids');
    if (userSelect) {
        // Add some basic styling for multi-select
        userSelect.style.height = '120px';
    }
});
</script>
@endsection
