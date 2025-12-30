@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Dashboard</h1>
            <div class="hidden sm:flex items-center gap-3 text-sm">
                <a href="{{ route('customers.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Customers</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('contacts.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Contacts</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('deals.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Deals</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('activities.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Activities</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Customers stat -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="w-full">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Customers</dt>
                                <dd class="mt-1">
                                    <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $counts['customers'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700 flex justify-center">
                    <a href="{{ route('customers.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-300 dark:hover:text-indigo-200">View all</a>
                </div>
            </div>

            <!-- Contacts stat -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="w-full">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Contacts</dt>
                                <dd class="mt-1">
                                    <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $counts['contacts'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700 flex justify-center">
                    <a href="{{ route('contacts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-300 dark:hover:text-indigo-200">View all</a>
                </div>
            </div>

            <!-- Deals stat -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="w-full">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Deals</dt>
                                <dd class="mt-1">
                                    <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $counts['deals'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700 flex justify-center">
                    <a href="{{ route('deals.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-300 dark:hover:text-indigo-200">View all</a>
                </div>
            </div>

            <!-- Activities stat -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-5">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="w-full">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Activities</dt>
                                <dd class="mt-1">
                                    <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $counts['activities'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700 flex justify-center">
                    <a href="{{ route('activities.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-300 dark:hover:text-indigo-200">View all</a>
                </div>
            </div>
        </div>

        <!-- Activity Lists -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Activities -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Activities</h3>
                    <a href="{{ route('activities.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">View all</a>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentActivities as $activity)
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-300 truncate">{{ $activity->subject }}</p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    @php
                                        $isPastPending = $activity->status === 'pending' && optional($activity->start_date)->isPast();
                                        $label = $isPastPending ? 'Not conducted' : ucfirst($activity->status);
                                        $badgeClass = match(true) {
                                            $activity->status === 'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            $activity->status === 'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            $isPastPending => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        };
                                    @endphp
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                        {{ $label }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $activity->start_date?->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 sm:px-6 text-gray-500 dark:text-gray-400 text-center">No recent activities</div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Activities -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Upcoming Activities</h3>
                    <a href="{{ route('calendar') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Open calendar</a>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($upcomingActivities as $activity)
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-300 truncate">{{ $activity->subject }}</p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ ucfirst($activity->type) }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $activity->start_date?->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 sm:px-6 text-gray-500 dark:text-gray-400 text-center">No upcoming activities</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 