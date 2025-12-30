@extends('admin.layouts.app')

@section('title', 'Audit Log Statistics')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <a href="{{ route('admin.audit-logs.index') }}"
                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Audit Logs
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-2">Audit Log Statistics</h1>
                </div>

                <!-- Period Selector -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.audit-logs.statistics', ['period' => 'day']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'day' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }}">
                        Today
                    </a>
                    <a href="{{ route('admin.audit-logs.statistics', ['period' => 'week']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'week' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }}">
                        This Week
                    </a>
                    <a href="{{ route('admin.audit-logs.statistics', ['period' => 'month']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'month' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }}">
                        This Month
                    </a>
                    <a href="{{ route('admin.audit-logs.statistics', ['period' => 'year']) }}"
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'year' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600' }}">
                        This Year
                    </a>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900 rounded-md p-3">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Logs</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($stats['total_logs']) }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($stats['unique_users']) }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-md p-3">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Suspicious Activities</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($stats['recent_suspicious']->count()) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Events Breakdown -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Events Breakdown</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @forelse($stats['by_event'] as $event)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span
                                            class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $event->event)) }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ number_format($event->count) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full"
                                            style="width: {{ ($event->count / $stats['total_logs']) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No events recorded</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Most Active Users</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @forelse($stats['by_user'] as $user)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">
                                                {{ substr($user->user_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user->user_name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 mr-2">{{ number_format($user->count) }}
                                            actions</span>
                                        @if($user->user_id)
                                            <a href="{{ route('admin.audit-logs.user-activity', $user->user_id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No user activity recorded</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Models Activity -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Activity by Model</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($stats['by_model'] as $model)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $model->model_name }}
                                    </h3>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ number_format($model->count) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 col-span-3">No model activity recorded</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Suspicious Activities -->
            @if($stats['recent_suspicious']->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Recent Suspicious Activities
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($stats['recent_suspicious'] as $log)
                            <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->description }}</p>
                                        <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $log->user_name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $log->ip_address }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.audit-logs.show', $log) }}"
                                        class="ml-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection