<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Dark mode script -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    <style>
        .modal-backdrop {
            z-index: 49;
        }
        .modal {
            z-index: 50;
        }
        
        /* Chat Widget Styles */
        .chat-widget-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99999;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .chat-widget-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
            border: 3px solid white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .chat-widget-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.6);
        }
        
        .chat-widget-button--open {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 8px 32px rgba(255, 107, 107, 0.4);
        }
        
        .chat-widget-icon {
            color: white;
            font-size: 24px;
            font-weight: bold;
            width: 24px;
            height: 24px;
        }
        
        .chat-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 380px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        
        .chat-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }
        
        .chat-header-content {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .chat-header-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .chat-header-avatar svg {
            width: 20px;
            height: 20px;
            color: #667eea;
        }
        
        .chat-header-text h3 {
            color: white;
            font-weight: 700;
            font-size: 16px;
            margin: 0 0 4px 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .chat-header-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            margin: 0;
            font-weight: 500;
        }
        
        .chat-close-button {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 8px;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
        }
        
        .chat-close-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .chat-close-button svg {
            width: 18px;
            height: 18px;
        }
        
        .chat-messages {
            height: 300px;
            overflow-y: auto;
            padding: 20px;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .message-container {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            animation: fadeInUp 0.3s ease-out;
        }
        
        .message-container--user {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .message-avatar-text {
            color: white;
        }
        
        .message-avatar-icon {
            width: 18px;
            height: 18px;
            color: white;
        }
        
        .message-content {
            flex: 1;
            margin: 0 12px;
            min-width: 0;
        }
        
        .message-bubble {
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            max-width: 85%;
            word-wrap: break-word;
            background: white;
            color: #334155;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            line-height: 1.5;
        }
        
        .message-bubble--user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: auto;
            border: none;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
        }
        
        .message-text {
            margin: 0;
            white-space: pre-line;
        }
        
        .message-timestamp {
            font-size: 11px;
            color: #94a3b8;
            margin: 6px 0 0 0;
            text-align: right;
            font-weight: 500;
        }
        
        .loading-dots {
            display: flex;
            gap: 6px;
            align-items: center;
            justify-content: center;
        }
        
        .loading-dot {
            width: 8px;
            height: 8px;
            background: #94a3b8;
            border-radius: 50%;
            animation: bounce 1.4s ease-in-out infinite both;
        }
        
        .loading-dot:nth-child(2) {
            animation-delay: 0.16s;
        }
        
        .loading-dot:nth-child(3) {
            animation-delay: 0.32s;
        }
        
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .chat-input {
            border-top: 1px solid #e2e8f0;
            padding: 20px;
            background: white;
        }
        
        .chat-form {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .chat-input-field {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
            color: #334155;
        }
        
        .chat-input-field:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .chat-input-field--loading {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .chat-send-button {
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .chat-send-button:hover:not(.chat-send-button--disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .chat-send-button--disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .chat-send-button svg {
            width: 18px;
            height: 18px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .chat-window {
                width: 320px;
                right: -10px;
            }
            
            .chat-messages {
                height: 250px;
                padding: 15px;
            }
            
            .chat-input {
                padding: 15px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        CRM
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('contacts.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('contacts.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Contacts
                    </a>
                    <a href="{{ route('deals.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('deals.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Deals
                    </a>
                    <a href="{{ route('pipeline.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('pipeline.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h6"/>
                        </svg>
                        Pipeline
                    </a>
                    <a href="{{ route('calendar') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('calendar') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Calendar
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Navigation -->
            <div class="sticky top-0 z-10 flex items-center h-16 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center ml-auto space-x-4">
                    <!-- Theme Toggle -->
                    <button 
                        @click="darkMode = !darkMode; localStorage.theme = darkMode ? 'dark' : 'light'; document.documentElement.classList.toggle('dark')" 
                        class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        title="Toggle theme"
                    >
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <!-- Admin Info with Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center" type="button">
                            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                            </div>
                            <!-- Dropdown arrow -->
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 py-1 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </div>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>

                    @yield('actions')
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6 bg-gray-50 dark:bg-gray-900">
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 dark:text-green-800 bg-green-100 dark:bg-green-200 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 dark:text-red-800 bg-red-100 dark:bg-red-200 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Chat Widget JavaScript Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatWidget', () => ({
                isOpen: false,
                messages: [],
                currentMessage: '',
                isLoading: false,
                
                                 init() {
                     this.messages.push({
                         type: 'bot',
                         text: 'Hello! I\'m your CRM Assistant. I can help you with:\n\n• Contact management\n• Deal tracking\n• Activity scheduling\n• Reports and analytics\n• **Download/Export reports** (Real Data!)\n• CRM best practices\n\n💡 **Quick Downloads:**\n📊 Deals Report | 📋 Contacts Report | 📈 Pipeline Report\n\nTry saying:\n"Download deals report" or "Export contacts report"\n\n✨ Now downloads your actual CRM data!',
                         timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                     });
                 },
                
                sendMessage() {
                    const message = this.currentMessage.trim();
                    if (!message || this.isLoading) return;

                    // Add user message
                    this.messages.push({
                        type: 'user',
                        text: message,
                        timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });

                    this.currentMessage = '';
                    this.isLoading = true;

                    // Check if this is a download request first
                    if (this.processDownloadRequest(message)) {
                        this.isLoading = false;
                        // Scroll to bottom
                        this.$nextTick(() => {
                            const chatContainer = this.$el.querySelector('.chat-messages');
                            if (chatContainer) {
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            }
                        });
                        return;
                    }

                    // Simulate AI response based on CRM keywords
                    setTimeout(() => {
                        const response = this.getCRMResponse(message.toLowerCase());
                        this.messages.push({
                            type: 'bot',
                            text: response,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                        this.isLoading = false;
                        
                        // Scroll to bottom
                        this.$nextTick(() => {
                            const chatContainer = this.$el.querySelector('.chat-messages');
                            if (chatContainer) {
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            }
                        });
                    }, 1000);
                },
                
                getCRMResponse(message) {
                    if (message.includes('contact') || message.includes('customer') || message.includes('client')) {
                        return 'For contact management, you can:\n\n• Add new contacts from the Contacts menu\n• Import contacts via CSV\n• Track contact interactions and history\n• Set contact status and priority\n• Create contact groups for better organization\n\nWould you like me to show you how to add a new contact?';
                    }
                    else if (message.includes('deal') || message.includes('opportunity') || message.includes('sales')) {
                        return 'For deal management:\n\n• Create new deals from the Deals menu\n• Set deal value and probability\n• Track deal stages (Prospecting → Qualification → Proposal → Negotiation → Closed)\n• Add activities and follow-ups\n• Monitor deal pipeline and forecasts\n\nNeed help with deal tracking?';
                    }
                    else if (message.includes('activity') || message.includes('task') || message.includes('follow') || message.includes('reminder')) {
                        return 'For activity management:\n\n• Schedule calls, meetings, and tasks\n• Set reminders and due dates\n• Track activity completion\n• Link activities to contacts/deals\n• View upcoming activities in Calendar\n\nI can help you schedule your next activity!';
                    }
                    else if (message.includes('report') || message.includes('analytics') || message.includes('dashboard') || message.includes('statistics')) {
                        return 'Your CRM dashboard shows:\n\n• Total contacts and deals\n• Sales pipeline overview\n• Recent activities\n• Performance metrics\n• Conversion rates\n\nCheck the Dashboard for real-time insights!';
                    }
                    else if (message.includes('download') || message.includes('export') || message.includes('csv') || message.includes('excel')) {
                        return 'I can help you download reports! Here are your options:\n\n📊 **Available Reports:**\n• Deals Report (CSV/Excel)\n• Contacts Report (CSV/Excel)\n• Sales Pipeline Report (CSV/Excel)\n• Activity Summary Report (CSV/Excel)\n\n💾 **Download Formats:**\n• CSV (Comma Separated Values)\n• Excel (.xlsx)\n• PDF Summary\n\nJust ask for the specific report you need, like:\n"Download deals report" or "Export contacts to CSV"';
                    }
                    else if (message.includes('help') || message.includes('how') || message.includes('what')) {
                        return 'I\'m here to help with your CRM! Here are common tasks:\n\n• Managing contacts and customers\n• Tracking sales deals and opportunities\n• Scheduling activities and follow-ups\n• Generating reports and analytics\n• **Downloading/Exporting reports**\n• CRM best practices and tips\n\nJust ask me about any specific area!';
                    }
                    else if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
                        return 'Hello! Welcome to your CRM system. I\'m here to help you manage your customer relationships, track sales, and stay organized.\n\nWhat would you like to work on today?';
                    }
                    else {
                        return 'I understand you\'re asking about "' + message + '". In your CRM, you can:\n\n• Manage contacts and customer information\n• Track sales deals and opportunities\n• Schedule activities and follow-ups\n• Generate reports and analytics\n• **Download and export data**\n\nCould you be more specific about what you need help with?';
                    }
                },
                
                downloadReport(type, format) {
                    console.log('Downloading real data for:', type, format);
                    
                    // Call the real API endpoints
                    let url = '';
                    let filename = '';
                    
                    if (type === 'deals') {
                        url = '{{ route("api.download.deals") }}';
                        filename = `deals_report_${new Date().toISOString().split('T')[0]}.csv`;
                    } else if (type === 'contacts') {
                        url = '{{ route("api.download.contacts") }}';
                        filename = `contacts_report_${new Date().toISOString().split('T')[0]}.csv`;
                    } else if (type === 'pipeline') {
                        url = '{{ route("api.download.pipeline") }}';
                        filename = `pipeline_report_${new Date().toISOString().split('T')[0]}.csv`;
                    }
                    
                    if (url) {
                        this.downloadFromAPI(url, filename, type);
                    }
                },
                
                downloadFromAPI(url, filename, type) {
                    try {
                        console.log('Downloading from API:', url);
                        
                        // Create a hidden link to trigger download
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = filename;
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        // Show success message
                        this.messages.push({
                            type: 'bot',
                            text: `✅ ${type.charAt(0).toUpperCase() + type.slice(1)} report downloaded successfully!\n\n📁 Filename: ${filename}\n💾 Format: CSV\n📊 Contains your real CRM data\n\nYour report is ready for analysis!`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                        
                    } catch (error) {
                        console.error('API download error:', error);
                        this.messages.push({
                            type: 'bot',
                            text: `❌ Error downloading ${type} report. Please try again or contact support.`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    }
                },
                
                downloadCSV(data, filename) {
                    try {
                        console.log('Downloading CSV:', filename, 'Data:', data); // Debug log
                        
                        const csvContent = data.map(row => 
                            row.map(cell => `"${cell}"`).join(',')
                        ).join('\n');
                        
                        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = filename;
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(link.href);
                        
                        this.messages.push({
                            type: 'bot',
                            text: `✅ Report downloaded successfully!\n\n📁 Filename: ${filename}\n📊 Rows: ${data.length - 1}\n💾 Format: CSV\n\nYour report is ready for analysis!`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    } catch (error) {
                        console.error('CSV download error:', error);
                        this.messages.push({
                            type: 'bot',
                            text: `❌ Error downloading CSV report. Please try again or contact support.`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    }
                },
                
                downloadExcel(data, filename) {
                    try {
                        console.log('Downloading Excel:', filename, 'Data:', data); // Debug log
                        
                        // For Excel, we'll create a simple HTML table that Excel can open
                        let html = '<table border="1">';
                        data.forEach(row => {
                            html += '<tr>';
                            row.forEach(cell => {
                                html += `<td>${cell}</td>`;
                            });
                            html += '</tr>';
                        });
                        html += '</table>';
                        
                        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = filename;
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(link.href);
                        
                        this.messages.push({
                            type: 'bot',
                            text: `✅ Excel report downloaded!\n\n📁 Filename: ${filename}\n📊 Rows: ${data.length - 1}\n💾 Format: Excel (.xlsx)\n\nOpen the file in Excel or Google Sheets for analysis!`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    } catch (error) {
                        console.error('Excel download error:', error);
                        this.messages.push({
                            type: 'bot',
                            text: `❌ Error downloading Excel report. Please try again or contact support.`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    }
                },
                
                downloadPDF(data, filename) {
                    // For PDF, we'll show a message that PDF generation would require a library
                    this.messages.push({
                        type: 'bot',
                        text: `📄 PDF Report Generation\n\nFor PDF reports, you would need to:\n• Use a PDF library like jsPDF\n• Or generate server-side with Laravel\n\nFor now, I recommend using CSV or Excel format for immediate download.\n\nWould you like to download in a different format?`,
                        timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                },
                
                processDownloadRequest(message) {
                    const lowerMessage = message.toLowerCase();
                    console.log('Processing download request:', message); // Debug log
                    
                    // Check for download/export keywords
                    if (lowerMessage.includes('download') || lowerMessage.includes('export') || lowerMessage.includes('get') || lowerMessage.includes('report')) {
                        let type = '';
                        let format = 'csv';
                        
                        // Determine report type with better detection
                        if (lowerMessage.includes('deal') || lowerMessage.includes('opportunity') || lowerMessage.includes('sales')) {
                            type = 'deals';
                        } else if (lowerMessage.includes('contact') || lowerMessage.includes('customer') || lowerMessage.includes('client')) {
                            type = 'contacts';
                        } else if (lowerMessage.includes('pipeline') || lowerMessage.includes('funnel')) {
                            type = 'pipeline';
                        }
                        
                        // Determine format
                        if (lowerMessage.includes('excel') || lowerMessage.includes('xlsx') || lowerMessage.includes('spreadsheet')) {
                            format = 'excel';
                        } else if (lowerMessage.includes('pdf') || lowerMessage.includes('document')) {
                            format = 'pdf';
                        } else {
                            format = 'csv';
                        }
                        
                        console.log('Detected type:', type, 'format:', format); // Debug log
                        
                        if (type) {
                            this.downloadReport(type, format);
                            return true;
                        }
                    }
                    return false;
                }
            }));
        });
    </script>

    <!-- Floating Chat Widget -->
    <div x-data="chatWidget" class="chat-widget-container">
        
        <!-- Chat Widget Button -->
        <button 
            @click="isOpen = !isOpen"
            class="chat-widget-button"
            :class="{ 'chat-widget-button--open': isOpen }"
            title="CRM Assistant"
        >
            <span x-show="!isOpen" class="chat-widget-icon">?</span>
            <svg x-show="isOpen" class="chat-widget-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Chat Window -->
        <div 
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="chat-window"
        >
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-header-content">
                    <div class="chat-header-avatar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="chat-header-text">
                        <h3>CRM Assistant</h3>
                        <p>Online</p>
                    </div>
                </div>
                <button @click="isOpen = false" class="chat-close-button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages">
                <template x-for="(message, index) in messages" :key="index">
                    <div class="message-container" :class="{ 'message-container--user': message.type === 'user' }">
                        <div class="message-avatar">
                            <span x-show="message.type === 'user'" class="message-avatar-text">U</span>
                            <svg x-show="message.type === 'bot'" class="message-avatar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble" :class="{ 'message-bubble--user': message.type === 'user' }">
                                <p x-text="message.text" class="message-text"></p>
                            </div>
                            <p class="message-timestamp" x-text="message.timestamp"></p>
                        </div>
                    </div>
                </template>
                
                <!-- Loading indicator -->
                <div x-show="isLoading" class="message-container">
                    <div class="message-avatar">
                        <svg class="message-avatar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="loading-dots">
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                                <div class="loading-dot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="chat-input">
                <form @submit.prevent="sendMessage()" class="chat-form">
                    <input 
                        type="text" 
                        x-model="currentMessage"
                        placeholder="Type your message..." 
                        class="chat-input-field"
                        :class="{ 'chat-input-field--loading': isLoading }"
                        :disabled="isLoading"
                        maxlength="300"
                    >
                    <button 
                        type="submit"
                        class="chat-send-button"
                        :class="{ 'chat-send-button--disabled': isLoading || !currentMessage.trim() }"
                        :disabled="isLoading || !currentMessage.trim()"
                    >
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    

    
    <!-- Debug: Check if Alpine.js is loaded -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            console.log('Alpine available:', typeof Alpine !== 'undefined');
            if (typeof Alpine !== 'undefined') {
                console.log('Alpine version:', Alpine.version);
            }
            
            // Check if our button exists
            const testButton = document.querySelector('button[onclick*="Chat widget is working"]');
            console.log('Test button found:', testButton);
            if (testButton) {
                console.log('Button position:', testButton.getBoundingClientRect());
                console.log('Button styles:', window.getComputedStyle(testButton));
            }
        });
    </script>
</body>
</html> 