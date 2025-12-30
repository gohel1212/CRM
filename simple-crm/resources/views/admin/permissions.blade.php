@extends('layouts.app')

@section('title', 'User Permissions')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Top Bar -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">User Permissions</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Permissions Content -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Users List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Users</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer user-item" 
                             data-user-id="{{ $user->id }}" 
                             data-user-name="{{ $user->name }}"
                             data-user-role="{{ $user->role }}"
                             data-user-permissions="{{ json_encode($user->permissions ?? []) }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Permissions Form -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Permissions</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select a user to manage their permissions</p>
                </div>
                <div class="p-6">
                    <div id="permissions-form" class="hidden">
                        <form action="{{ route('admin.permissions.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" id="selected-user-id">
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Selected User: <span id="selected-user-name"></span></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Role: <span id="selected-user-role"></span></p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">CRM Permissions</h5>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="customers.view" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">View Customers</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="customers.create" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Create Customers</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="customers.edit" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Edit Customers</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="customers.delete" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Delete Customers</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Deals Permissions</h5>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="deals.view" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">View Deals</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="deals.create" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Create Deals</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="deals.edit" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Edit Deals</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="deals.delete" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Delete Deals</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Pipeline Permissions</h5>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="pipeline.view" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">View Pipeline</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="pipeline.manage" class="permission-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Manage Pipeline</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Update Permissions
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="no-user-selected" class="text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <p class="mt-2">Select a user to manage their permissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userItems = document.querySelectorAll('.user-item');
    const permissionsForm = document.getElementById('permissions-form');
    const noUserSelected = document.getElementById('no-user-selected');
    const selectedUserId = document.getElementById('selected-user-id');
    const selectedUserName = document.getElementById('selected-user-name');
    const selectedUserRole = document.getElementById('selected-user-role');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    userItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            userItems.forEach(i => i.classList.remove('bg-indigo-50', 'dark:bg-indigo-900'));
            
            // Add active class to clicked item
            this.classList.add('bg-indigo-50', 'dark:bg-indigo-900');
            
            // Show permissions form
            permissionsForm.classList.remove('hidden');
            noUserSelected.classList.add('hidden');
            
            // Set user data
            selectedUserId.value = this.dataset.userId;
            selectedUserName.textContent = this.dataset.userName;
            selectedUserRole.textContent = this.dataset.userRole;
            
            // Clear all checkboxes
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Set permissions from user data
            const userPermissions = JSON.parse(this.dataset.userPermissions);
            userPermissions.forEach(permission => {
                const checkbox = document.querySelector(`input[value="${permission}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        });
    });
});
</script>
@endsection
