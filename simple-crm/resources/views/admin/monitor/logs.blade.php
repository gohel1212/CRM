@extends('admin.layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Logs</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Monitor and analyze system logs for debugging and monitoring.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1">
                <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Log Level</label>
                <select name="level" id="level" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all" {{ $level === 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="debug" {{ $level === 'debug' ? 'selected' : '' }}>Debug</option>
                    <option value="info" {{ $level === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="warning" {{ $level === 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="error" {{ $level === 'error' ? 'selected' : '' }}>Error</option>
                    <option value="critical" {{ $level === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input type="date" name="date" id="date" value="{{ $date }}" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter Logs
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">System Logs</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Showing logs for {{ $date }} with level: {{ $level === 'all' ? 'All' : ucfirst($level) }}</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Timestamp
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($log['timestamp'])->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($log['level'] === 'ERROR' || $log['level'] === 'CRITICAL') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($log['level'] === 'WARNING') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($log['level'] === 'INFO') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                    {{ $log['level'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div class="max-w-md">
                                    <p class="truncate">{{ $log['message'] }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No logs found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No logs match your current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Log Statistics -->
    @if(count($logs) > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Log Statistics</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-4">
                @php
                    $logCounts = collect($logs)->groupBy('level')->map->count();
                @endphp
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logCounts->get('ERROR', 0) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Errors</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logCounts->get('WARNING', 0) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Warnings</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logCounts->get('INFO', 0) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Info</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($logs) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
