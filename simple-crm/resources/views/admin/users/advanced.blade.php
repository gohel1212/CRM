@extends('admin.layouts.app')

@section('title', 'Advanced User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage system users with advanced features and bulk operations.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex items-center space-x-3">
            <a href="{{ route('admin.users.export') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 opacity-100 relative z-10">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Users
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add User
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['total_users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['active_users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Admin Users</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['admin_users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Recent Signups</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['recent_signups'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Search users</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white" 
                           placeholder="Search users by name or email...">
                </div>
            </div>
            
            <div class="sm:flex sm:space-x-4">
                <select name="role" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                
                <select name="status" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" id="bulk-actions" style="display: none;">
        <form method="POST" action="{{ route('admin.users.bulk') }}" id="bulk-form">
            @csrf
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-700 dark:text-gray-300" id="selected-count">0 users selected</span>
                <select name="action" class="block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Choose action...</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="change_role">Change Role</option>
                    <option value="delete">Delete</option>
                </select>
                <select name="new_role" class="block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="display: none;">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Apply
                </button>
                <button type="button" onclick="clearSelection()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Users</h3>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="select-all" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                    <label for="select-all" class="text-sm text-gray-700 dark:text-gray-300">Select All</label>
                </div>
            </div>
        </div>
        
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $user)
                <li class="px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                            
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $user->name }}
                                    </p>
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            User
                                        </span>
                                    @endif
                                    
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $user->email }}</span>
                                    @if($user->department)
                                        <span>• {{ $user->department }}</span>
                                    @endif
                                    @if($user->phone)
                                        <span>• {{ $user->phone }}</span>
                                    @endif
                                </div>
                                
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Joined {{ $user->created_at->format('M j, Y') }} • 
                                    {{ $user->activities_count ?? 0 }} activities
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.users.details', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new user.</p>
                </li>
            @endforelse
        </ul>
        
        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const actionSelect = document.querySelector('select[name="action"]');
    const newRoleSelect = document.querySelector('select[name="new_role"]');
    const bulkForm = document.getElementById('bulk-form');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            updateSelectAllState();
        });
    });

    // Action select change
    actionSelect.addEventListener('change', function() {
        if (this.value === 'change_role') {
            newRoleSelect.style.display = 'block';
        } else {
            newRoleSelect.style.display = 'none';
        }
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = count + ' user' + (count > 1 ? 's' : '') + ' selected';
            
            // Add user IDs to form
            const userIdsInput = document.querySelector('input[name="user_ids[]"]');
            if (userIdsInput) {
                userIdsInput.remove();
            }
            
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const totalBoxes = userCheckboxes.length;
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === totalBoxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    function clearSelection() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        updateBulkActions();
    }

    // Make clearSelection global
    window.clearSelection = clearSelection;
});
</script>
@endsection
