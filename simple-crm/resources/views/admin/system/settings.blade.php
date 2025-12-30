@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure application settings and preferences.</p>
    </div>

    <!-- Settings Form -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <form action="{{ route('admin.system.settings.update') }}" method="POST" class="space-y-6 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Application Name</label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('app_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Application URL</label>
                    <input type="url" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('app_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Address</label>
                    <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', config('mail.from.address')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('mail_from_address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail From Name</label>
                    <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', config('mail.from.name')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('mail_from_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Settings
                </button>
            </div>
        </form>
    </div>

    <!-- System Information -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">System Information</h3>
            <div class="mt-5">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ PHP_VERSION }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ app()->version() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ app()->environment() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Debug Mode</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
