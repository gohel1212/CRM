@extends('admin.layouts.app')

@section('title', 'Cache Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-indigo-600">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </span>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cache Management</h1>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Clear application cache to improve performance and resolve issues.</p>
    </div>

    <!-- Cache Actions -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Clear Config Cache -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Config Cache</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">Settings</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('admin.system.cache.clear') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="config">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                            Clear Config
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Clear Route Cache -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Route Cache</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">URLs</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('admin.system.cache.clear') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="route">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                            Clear Routes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Clear View Cache -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">View Cache</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Templates
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('admin.system.cache.clear') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="view">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                            Clear Views
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Cache Information</h3>
            <div class="mt-5">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Application Cache</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">Stores compiled views, routes, and configuration</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Session Cache</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">User session data and temporary storage</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Query Cache</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">Database query results and ORM cache</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">File Cache</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">Cached files and static content</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
