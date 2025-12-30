@extends('admin.layouts.app')

@section('title', 'User Activity - ' . $user->name)

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Audit Logs
                </a>
                <div class="flex items-center mt-2">
                    <div
                        class="flex-shrink-0 h-12 w-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                        <span class="text-xl font-medium text-indigo-600 dark:text-indigo-300">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Actions</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ number_format($stats['total_actions']) }}</dd>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Today</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ number_format($stats['today_actions']) }}</dd>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">This Week</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ number_format($stats['this_week_actions']) }}</dd>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Activity</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($stats['last_activity'])
                            {{ $stats['last_activity']->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </dd>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Activity Timeline</h2>
                </div>

                <div class="px-6 py-4">
                    @forelse($logs as $log)
                        <div
                            class="relative pb-8 {{ !$loop->last ? 'border-l-2 border-gray-200 dark:border-gray-700' : '' }} ml-4">
                            <div class="absolute -left-[9px] top-0">
                                @php
                                    $iconBg = match ($log->event) {
                                        'created' => 'bg-green-500',
                                        'updated' => 'bg-blue-500',
                                        'deleted' => 'bg-red-500',
                                        'login' => 'bg-indigo-500',
                                        'logout' => 'bg-gray-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <div class="h-4 w-4 rounded-full {{ $iconBg }} border-2 border-white dark:border-gray-800">
                                </div>
                            </div>

                            <div class="ml-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $log->description }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                {{ $log->event_name }}
                                            </span>
                                            @if($log->model_name)
                                                <span>{{ $log->model_name }} #{{ $log->auditable_id }}</span>
                                            @endif
                                            <span>{{ $log->ip_address }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex items-center gap-3">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at->format('M d, h:i A') }}
                                        </span>
                                        <a href="{{ route('admin.audit-logs.show', $log) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-xs">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No activity recorded</p>
                        </div>
                    @endforelse
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