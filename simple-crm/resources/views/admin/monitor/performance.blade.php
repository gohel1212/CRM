@extends('admin.layouts.app')

@section('title', 'Performance Monitor')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Performance Monitor</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Monitor system performance metrics and database statistics.</p>
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

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Response Time History</h3>
                <div class="h-64 flex items-end justify-between space-x-2">
                    @foreach($metrics['response_times'] as $time)
                        <div class="flex-1 bg-indigo-500 rounded-t" style="height: {{ ($time / 500) * 100 }}%"></div>
                    @endforeach
                </div>
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Average: {{ array_sum($metrics['response_times']) / count($metrics['response_times']) }}ms
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Memory Usage History</h3>
                <div class="h-64 flex items-end justify-between space-x-2">
                    @foreach($metrics['memory_usage_history'] as $usage)
                        <div class="flex-1 bg-green-500 rounded-t" style="height: {{ $usage }}%"></div>
                    @endforeach
                </div>
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Current: {{ number_format(memory_get_usage(true) / 1024 / 1024, 1) }}MB
                </div>
            </div>
        </div>
    </div>

    <!-- Database Performance -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Performance</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Database statistics and query performance metrics.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($databaseStats['tables']) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Tables</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($databaseStats['total_size'] / 1024 / 1024, 1) }}MB</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Database Size</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $databaseStats['connection_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Active Connections</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ array_sum($metrics['database_query_times']) / count($metrics['database_query_times']) }}ms</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Avg Query Time</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Tables -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Tables</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Table sizes and row counts.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Table Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Rows
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Size
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Engine
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($databaseStats['tables'] as $table)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $table['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($table['rows']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($table['size'] / 1024, 1) }}KB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $table['engine'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cache Performance -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Cache Performance</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cache statistics and performance metrics.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cacheStats['driver'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Cache Driver</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cacheStats['hit_rate'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Hit Rate</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cacheStats['memory_usage'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Memory Usage</div>
                </div>
            </div>
            
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Cache Statistics</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['cache_performance']['hits']) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Cache Hits</div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['cache_performance']['misses']) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Cache Misses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshData() {
    window.location.reload();
}

// Auto-refresh every 60 seconds
setInterval(function() {
    // You could implement AJAX refresh here instead of full page reload
    // refreshData();
}, 60000);
</script>
@endsection
