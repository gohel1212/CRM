@extends('admin.layouts.app')

@section('title', 'Audit Log Details')

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Audit Logs
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-2">Audit Log Details</h1>
            </div>

            <!-- Main Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Event Information</h2>
                        @php
                            $badgeClass = match ($auditLog->event) {
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
                            {{ $auditLog->event_name }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $auditLog->created_at->timezone(config('app.timezone'))->format('F d, Y \\a\\t h:i A') }}
                                <span
                                    class="text-gray-500 dark:text-gray-400">({{ $auditLog->created_at->timezone(config('app.timezone'))->diffForHumans() }})</span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">
                                            {{ substr($auditLog->user_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div>{{ $auditLog->user_name }}</div>
                                        @if($auditLog->user)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $auditLog->user->email }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </dd>
                        </div>

                        @if($auditLog->model_name)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Model Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $auditLog->model_name }} #{{ $auditLog->auditable_id }}
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $auditLog->ip_address }}</dd>
                        </div>

                        @if($auditLog->url)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 break-all">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-xs font-mono">
                                        {{ $auditLog->method }}
                                    </span>
                                    <span class="ml-2">{{ $auditLog->url }}</span>
                                </dd>
                            </div>
                        @endif

                        @if($auditLog->description)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $auditLog->description }}</dd>
                            </div>
                        @endif

                        @if($auditLog->user_agent)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</dt>
                                <dd class="mt-1 text-xs text-gray-600 dark:text-gray-400 font-mono break-all">
                                    {{ $auditLog->user_agent }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Changes Card -->
            @if($auditLog->old_values || $auditLog->new_values)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Changes</h2>
                    </div>

                    <div class="px-6 py-4">
                        @php
                            $changes = $auditLog->getChangesSummary();
                        @endphp

                        @if($changes)
                            <div class="space-y-4">
                                @foreach($changes as $field => $values)
                                    <div class="border-l-4 border-indigo-500 pl-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Old Value</div>
                                                <div
                                                    class="text-sm text-gray-900 dark:text-gray-100 bg-red-50 dark:bg-red-900/20 px-3 py-2 rounded">
                                                    @if(is_array($values['old']))
                                                        <pre class="text-xs">{{ json_encode($values['old'], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $values['old'] ?? 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">New Value</div>
                                                <div
                                                    class="text-sm text-gray-900 dark:text-gray-100 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded">
                                                    @if(is_array($values['new']))
                                                        <pre class="text-xs">{{ json_encode($values['new'], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $values['new'] ?? 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Show all new values for created events -->
                            @if($auditLog->event === 'created' && $auditLog->new_values)
                                <div class="space-y-2">
                                    @foreach($auditLog->new_values as $field => $value)
                                        @if(!in_array($field, ['created_at', 'updated_at', 'id']))
                                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                </span>
                                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                                    @if(is_array($value))
                                                        <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value ?? 'N/A' }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Show all old values for deleted events -->
                            @if($auditLog->event === 'deleted' && $auditLog->old_values)
                                <div class="space-y-2">
                                    @foreach($auditLog->old_values as $field => $value)
                                        @if(!in_array($field, ['created_at', 'updated_at', 'deleted_at']))
                                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                </span>
                                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                                    @if(is_array($value))
                                                        <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value ?? 'N/A' }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <!-- Properties Card -->
            @if($auditLog->properties)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Additional Properties</h2>
                    </div>

                    <div class="px-6 py-4">
                        <pre
                            class="text-xs text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900 p-4 rounded overflow-x-auto">{{ json_encode($auditLog->properties, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection