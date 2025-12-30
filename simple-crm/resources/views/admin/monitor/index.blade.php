@extends('admin.layouts.app')

@section('title', 'System Monitor')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Monitor</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Real-time system monitoring and performance metrics.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button onclick="refreshData()" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- System Statistics -->
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
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ number_format($systemStats['total_users']) }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">₹{{ number_format($systemStats['revenue']) }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Memory Usage</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ number_format($systemStats['memory_usage']['used'] / 1024 / 1024, 1) }}MB</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Disk Usage</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $systemStats['disk_usage']['percentage'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Performance Metrics</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Response Time</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $performanceMetrics['avg_response_time'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Requests/Min</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $performanceMetrics['requests_per_minute'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Error Rate</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $performanceMetrics['error_rate'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cache Hit Rate</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $performanceMetrics['cache_hit_rate'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">System Health</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">CPU Usage</span>
                            <span class="text-gray-900 dark:text-white">45%</span>
                        </div>
                        <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Memory Usage</span>
                            <span class="text-gray-900 dark:text-white">{{ number_format($systemStats['memory_usage']['used'] / 1024 / 1024, 1) }}MB</span>
                        </div>
                        <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($systemStats['memory_usage']['used'] / $systemStats['memory_usage']['peak']) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Disk Usage</span>
                            <span class="text-gray-900 dark:text-white">{{ $systemStats['disk_usage']['percentage'] }}%</span>
                        </div>
                        <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $systemStats['disk_usage']['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs and User Activity -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Logs -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent System Logs</h3>
                    <a href="{{ route('admin.monitor.logs') }}" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">View all</a>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if(count($recentLogs) > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($recentLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                                    @if($log['level'] === 'ERROR' || $log['level'] === 'CRITICAL') bg-red-500
                                                    @elseif($log['level'] === 'WARNING') bg-yellow-500
                                                    @else bg-green-500 @endif">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $log['level'] }}:</span>
                                                        {{ Str::limit($log['message'], 100) }}
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time datetime="{{ $log['timestamp'] }}">
                                                        {{ optional(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log['timestamp']))?->diffForHumans() ?? $log['timestamp'] }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent logs</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">System is running smoothly.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Activity -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Active Users</h3>
                    <a href="{{ route('admin.monitor.users') }}" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">View all</a>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if(count($userActivity) > 0)
                    <div class="space-y-4">
                        @foreach($userActivity as $user)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->activities_count }} activities</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No active users</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No users have been active recently.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Error Logs -->
    @if(count($errorLogs) > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Errors</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        {{ count($errorLogs) }} errors
                    </span>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    @foreach($errorLogs as $error)
                        <div class="border-l-4 border-red-400 bg-red-50 dark:bg-red-900/20 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800 dark:text-red-200">
                                        <span class="font-medium">{{ $error['level'] }}:</span>
                                        {{ $error['message'] }}
                                    </p>
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-300">
                                        {{ optional(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $error['timestamp']))?->format('M j, Y g:i A') ?? $error['timestamp'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function refreshData() {
    // Reload the page to get fresh data
    window.location.reload();
}

// Auto-refresh every 30 seconds
setInterval(function() {
    // You could implement AJAX refresh here instead of full page reload
    // refreshData();
}, 30000);
</script>
@endsection
