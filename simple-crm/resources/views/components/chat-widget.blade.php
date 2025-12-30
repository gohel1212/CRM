<!-- Simple Test Button First -->
<div class="fixed bottom-4 right-4 z-[9999]">
    <button 
        onclick="alert('Chat widget is working!')"
        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-4 shadow-lg border-2 border-white"
        title="Test Chat Widget"
        style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </button>
</div>

<!-- Full Chat Widget -->
<div x-data="{ 
    isOpen: false, 
    messages: [],
    currentMessage: '',
    isLoading: false,
    init() {
        // Add welcome message
        this.messages.push({
            type: 'bot',
            text: 'Hi! I\'m your CRM assistant. How can I help you today?',
            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });
        console.log('Chat widget initialized');
    }
}" class="fixed bottom-20 right-4 z-[9999]">
    
    <!-- Chat Widget Button -->
    <button 
        @click="isOpen = !isOpen"
        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-3 shadow-lg transition-all duration-200 hover:scale-110 border-2 border-white"
        :class="{ 'bg-red-500 hover:bg-red-600': isOpen }"
        title="AI Assistant"
        style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);"
    >
        <svg x-show="!isOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="isOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        class="absolute bottom-14 right-0 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700"
    >
        <!-- Chat Header -->
        <div class="bg-indigo-600 dark:bg-indigo-700 px-3 py-2 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-xs">CRM Assistant</h3>
                        <p class="text-indigo-100 text-xs">Online</p>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-white hover:text-indigo-100">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="h-48 overflow-y-auto p-3 space-y-2">
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex items-start space-x-2" :class="{ 'flex-row-reverse space-x-reverse': message.type === 'user' }">
                    <div class="flex-shrink-0">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs"
                             :class="message.type === 'user' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400'">
                            <span x-show="message.type === 'user'">U</span>
                            <svg x-show="message.type === 'bot'" class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="px-2 py-1.5 rounded-lg text-xs"
                             :class="message.type === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'">
                            <p x-text="message.text"></p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="message.timestamp"></p>
                    </div>
                </div>
            </template>
            
            <!-- Loading indicator -->
            <div x-show="isLoading" class="flex items-start space-x-2">
                <div class="flex-shrink-0">
                    <div class="w-5 h-5 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="bg-gray-100 dark:bg-gray-700 px-2 py-1.5 rounded-lg">
                        <div class="flex space-x-1">
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-2">
            <form @submit.prevent="sendMessage()" class="flex space-x-2">
                <input 
                    type="text" 
                    x-model="currentMessage"
                    placeholder="Type your message..." 
                    class="flex-1 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                    :disabled="isLoading"
                    maxlength="300"
                >
                <button 
                    type="submit"
                    class="px-2 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    :disabled="isLoading || !currentMessage.trim()"
                >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function sendMessage() {
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

    // Send to server
    fetch('{{ route("chatbot.chat") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.messages.push({
                type: 'bot',
                text: data.message,
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
        } else {
            this.messages.push({
                type: 'bot',
                text: 'Sorry, I encountered an error. Please try again.',
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        this.messages.push({
            type: 'bot',
            text: 'Sorry, I encountered an error. Please try again.',
            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });
    })
    .finally(() => {
        this.isLoading = false;
        // Scroll to bottom
        this.$nextTick(() => {
            const chatContainer = this.$el.querySelector('.overflow-y-auto');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    });
}
</script>
