@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage system users and their permissions.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.users.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Add user
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $user)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                    Edit
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium" 
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No users found.
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection