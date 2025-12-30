@extends('admin.layouts.app')

@section('title', 'Database Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Database Management</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor database tables and create backups.</p>
    </div>

    <!-- Database Tables -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Tables</h3>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($tables as $table)
                <li class="px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $table['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($table['count']) }} records</div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    No tables found.
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Backup Actions -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Backup</h3>
            <div class="mt-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a backup of your database.</p>
                <div class="mt-4">
                    <form action="{{ route('admin.system.backup') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Create Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
