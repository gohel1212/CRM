@extends('layouts.app')

@section('title', 'Pipeline')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="kanban()">
        <!-- Top Bar -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Pipeline</h1>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <!-- Export Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                style="background-color: #059669 !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: flex; align-items: center; font-weight: 500; font-size: 0.875rem;"
                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded flex items-center"
                                onmouseover="this.style.backgroundColor='#047857'"
                                onmouseout="this.style.backgroundColor='#059669'">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="margin-right: 0.5rem; width: 1rem; height: 1rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Export Data
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="margin-left: 0.5rem; width: 1rem; height: 1rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('api.download.pipeline') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        Pipeline Report (CSV)
                                    </a>
                                    <a href="{{ route('api.download.deals') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        All Deals (CSV)
                                    </a>
                                    <a href="{{ route('api.download.contacts') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        All Contacts (CSV)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('deals.create') }}"
                        style="background-color: #4f46e5 !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; display: inline-block;"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded"
                        onmouseover="this.style.backgroundColor='#4338ca'"
                        onmouseout="this.style.backgroundColor='#4f46e5'">New Deal</a>
                </div>
            </div>
        </div>

        <!-- Pipeline Board -->
        <div class="p-6">
            <div class="flex space-x-6 overflow-x-auto pb-4">
                @if(isset($stages) && $stages->count() > 0)
                    @foreach ($stages as $stage)
                        <div class="flex-shrink-0 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                            :data-stage-id="{{ $stage->id }}">
                            <!-- Stage Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full" style="background: {{ $stage->color }}"></div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stage->name }}</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        ₹{{ number_format($stage->total_value ?? 0) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $stage->deals->count() }} deals</div>
                                </div>
                            </div>

                            <!-- Stage Content -->
                            <div class="min-h-[500px] space-y-3" x-ref="stage_{{ $stage->id }}" data-stage-id="{{ $stage->id }}"
                                @dragover.prevent @drop="onDrop($event, {{ $stage->id }})">

                                @if($stage->deals->count() > 0)
                                    @foreach ($stage->deals as $deal)
                                        <div class="bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-move hover:bg-gray-200 dark:hover:bg-gray-650 transition-colors"
                                            draggable="true" @dragstart="onDragStart($event, {{ $deal->id }})">

                                            <!-- Deal Title -->
                                            <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ $deal->name }}</div>

                                            <!-- Company Info -->
                                            @if($deal->customer)
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">{{ $deal->customer->name }}</div>
                                            @endif

                                            <!-- Deal Value -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span
                                                        class="text-xs text-gray-700 dark:text-gray-300">₹{{ number_format($deal->amount) }}</span>
                                                </div>

                                                <!-- Action Button -->
                                                <button
                                                    class="w-6 h-6 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Empty State -->
                                    <div
                                        class="flex items-center justify-center h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                        <div class="text-center">
                                            <div class="w-8 h-8 mx-auto mb-2 text-gray-400 dark:text-gray-500">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Drop deals here</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-gray-500 dark:text-gray-400 text-center py-12">
                        <p>No stages available. Please create a pipeline first.</p>
                        <a href="{{ route('deals.create') }}"
                            class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">Create
                            Deal</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function kanban() {
            return {
                stages: [],
                draggingDealId: null,
                onDragStart(event, dealId) {
                    this.draggingDealId = dealId;
                    event.dataTransfer.setData('text/plain', String(dealId));
                },
                async onDrop(event, toStageId) {
                    const dealId = Number(event.dataTransfer.getData('text/plain') || this.draggingDealId);
                    const container = event.currentTarget;
                    const toPosition = container.querySelectorAll('[draggable="true"]').length;

                    try {
                        await fetch('{{ route('pipeline.move') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ deal_id: dealId, to_stage_id: toStageId, to_position: toPosition })
                        });
                        // Optimistic UI: reload to reflect new lists simply
                        window.location.reload();
                    } catch (e) {
                        alert('Failed to move deal');
                    }
                }
            }
        }
    </script>
@endsection