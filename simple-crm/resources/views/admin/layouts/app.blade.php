<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'CRM Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        /* Fallback CSS to prevent sidebar overlap even if Tailwind build is stale */
        @media (min-width: 1024px) {
            .content-with-sidebar { padding-left: 16rem; }
        }
        @media (max-width: 1023.98px) {
            .content-with-sidebar[data-sidebar-open="true"] { padding-left: 16rem; }
        }
    </style>

    @auth
    <script>
        window.App = {
            user: {
                id: {{ auth()->id() }},
                role: "{{ auth()->user()->role }}",
                permissions: @json(auth()->user()->permissions ?? [])
            }
        };
        window.can = function(p) {
            try {
                const u = window.App?.user;
                return !!u && (u.role === 'admin' || (u.permissions || []).includes(p));
            } catch (e) { return false; }
        };
    </script>
    @endauth
</head>

<body class="h-full bg-gray-100" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-900/80 lg:hidden z-40" style="display: none;"></div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50"
        :class="{ 'translate-x-0': sidebarOpen }">

        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="ml-3 text-xl font-bold text-gray-900 dark:text-white">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-3">
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Audit Logs & Activity Tracking
                </a>


                <a href="{{ route('admin.users.advanced') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    User Management
                </a>

                <a href="{{ route('admin.permissions.index') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.permissions.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Permissions
                </a>

                <a href="{{ route('admin.monitor.index') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.monitor.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    System Monitor
                </a>

                <a href="{{ route('admin.system.logs') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.logs') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    System Logs
                </a>

                <a href="{{ route('admin.system.cache') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.cache') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Cache Management
                </a>

                <a href="{{ route('admin.system.database') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.database') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                        </path>
                    </svg>
                    Database
                </a>

                <a href="{{ route('admin.system.settings') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.settings') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>

            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-all duration-300 content-with-sidebar" :data-sidebar-open="sidebarOpen ? 'true' : 'false'">
        <!-- Top Navigation -->
        <div
            class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden"
                @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                <div class="relative flex flex-1 items-center">
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Admin Panel</h1>
                </div>
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-x-2 text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                <span
                                    class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:flex lg:items-center">
                                {{ auth()->user()->name }}
                                <svg class="ml-2 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700/5 focus:outline-none">
                            <a href="{{ route('admin.system.settings') }}"
                                class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700">Settings</a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>