@extends('admin.layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Audit Logs</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track all user actions and system changes</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.audit-logs.statistics') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistics
                </a>
                <form action="{{ route('admin.audit-logs.export') }}" method="GET" class="inline">
                    @foreach(request()->except('_token') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </form>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search description or user..."
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- User Filter -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                            <select name="user_id" 
                                    id="user_id" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Event Filter -->
                        <div>
                            <label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event</label>
                            <select name="event" 
                                    id="event" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Events</option>
                                @foreach($events as $evt)
                                    <option value="{{ $evt }}" {{ request('event') == $evt ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $evt)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Model Filter -->
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model</label>
                            <select name="model" 
                                    id="model" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Models</option>
                                @foreach($models as $model)
                                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                            <input type="date" 
                                   name="date_from" 
                                   id="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                            <input type="date" 
                                   name="date_to" 
                                   id="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.audit-logs.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Clear Filters
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date/Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Event
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>{{ $log->created_at->timezone(config('app.timezone'))->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->timezone(config('app.timezone'))->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">
                                                {{ substr($log->user_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->user_name }}</div>
                                            @if($log->user)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeClass = match($log->event) {
                                            'created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'login' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                            'logout' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                            'login_failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'suspicious_activity' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ $log->event_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs truncate">{{ $log->description }}</div>
                                    @if($log->model_name)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $log->model_name }} #{{ $log->auditable_id }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2">No audit logs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection