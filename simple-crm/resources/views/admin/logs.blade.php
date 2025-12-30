@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Top Bar -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">System Logs</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <button onclick="refreshLogs()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Logs Content -->
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Logs</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Error</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Warning</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Info</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($logs as $log)
                    <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <div class="px-6 py-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 rounded-full mt-2 
                                        {{ $log['level'] === 'error' ? 'bg-red-500' : 
                                           ($log['level'] === 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst($log['level']) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log['timestamp'] }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 break-words">
                                        {{ $log['message'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No logs found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No system logs are available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Log Statistics -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Errors</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ collect($logs)->where('level', 'error')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Warnings</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ collect($logs)->where('level', 'warning')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Info</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ collect($logs)->where('level', 'info')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    window.location.reload();
}
</script>
@endsection
