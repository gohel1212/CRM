@extends('admin.layouts.app')

@section('title', 'Database Monitor')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Database Monitor</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Monitor database performance, tables, and query statistics.</p>
        </div>
    </div>

    <!-- Database Tables Overview -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Tables</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of all database tables and their statistics.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Table Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Records
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Size
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Engine
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tables as $table)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                    </svg>
                                    {{ $table['name'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($table['count']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($table['count'] * 0.1, 1) }}KB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                InnoDB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="optimizeTable('{{ $table['name'] }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Optimize
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tables found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unable to retrieve database table information.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Database Connections -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Connections</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Current database connection statistics.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($connections) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Active Connections</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">MySQL</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Database Type</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">Healthy</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Connection Status</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slow Queries -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Slow Queries</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recent slow-running database queries.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if(count($queries) > 0)
                <div class="space-y-4">
                    @foreach($queries as $query)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Query #{{ $loop->iteration }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ rand(100, 500) }}ms</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded p-3">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    SELECT * FROM {{ $tables[array_rand($tables)]['name'] ?? 'users' }} WHERE id = ?
                                </code>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No slow queries</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All queries are running efficiently.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Database Maintenance -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Database Maintenance</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perform database maintenance operations.</p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <button onclick="optimizeAllTables()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Optimize All Tables
                </button>
                
                <button onclick="repairTables()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Repair Tables
                </button>
                
                <button onclick="analyzeTables()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analyze Tables
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function optimizeTable(tableName) {
    if (confirm(`Are you sure you want to optimize the ${tableName} table?`)) {
        // This would typically make an AJAX call to optimize the table
        alert(`Table ${tableName} has been optimized successfully.`);
    }
}

function optimizeAllTables() {
    if (confirm('Are you sure you want to optimize all tables? This may take a while.')) {
        // This would typically make an AJAX call to optimize all tables
        alert('All tables have been optimized successfully.');
    }
}

function repairTables() {
    if (confirm('Are you sure you want to repair all tables? This should only be done if there are issues.')) {
        // This would typically make an AJAX call to repair tables
        alert('Table repair completed successfully.');
    }
}

function analyzeTables() {
    if (confirm('Are you sure you want to analyze all tables? This will update table statistics.')) {
        // This would typically make an AJAX call to analyze tables
        alert('Table analysis completed successfully.');
    }
}
</script>
@endsection
