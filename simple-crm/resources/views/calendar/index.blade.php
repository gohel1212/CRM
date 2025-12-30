@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
    <style>
        /* Modal styles */
        #addActivityModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        #addActivityModal .modal-content {
            background-color: white;
            position: relative;
            z-index: 10000;
        }

        .modal-open {
            overflow: hidden;
        }
    </style>

    <div class="max-w-6xl mx-auto">
        <!-- Back button -->
        <div class="mb-4">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex items-center justify-between mb-4">
                    <div class="flex space-x-4">
                        <button data-navigate data-date="{{ now()->format('Y-m-d') }}"
                            class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                            Today
                        </button>
                        <div class="flex items-center space-x-2">
                            <button data-navigate data-date="{{ $calendar['previousDate'] }}"
                                class="p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button data-navigate data-date="{{ $calendar['nextDate'] }}"
                                class="p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $calendar['date']->format('F Y') }}
                    </h2>
                </div>

                <div
                    class="grid grid-cols-7 gap-px mt-4 text-sm text-center text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 rounded-lg">
                    <div class="py-2 bg-white dark:bg-gray-800">Sun</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Mon</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Tue</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Wed</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Thu</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Fri</div>
                    <div class="py-2 bg-white dark:bg-gray-800">Sat</div>
                </div>

                <div class="grid grid-cols-7 gap-px mt-px bg-gray-200 dark:bg-gray-700 rounded-lg">
                    @php
                        $currentDay = $calendar['startOfMonth']->copy()->startOfWeek();
                        $endDay = $calendar['endOfMonth']->copy()->endOfWeek();
                    @endphp

                    @while($currentDay <= $endDay)
                        @php
                            $isCurrentMonth = $currentDay->month === $calendar['date']->month;
                            $isToday = $currentDay->isToday();
                            $dayActivities = $activities->filter(function ($activity) use ($currentDay) {
                                return $activity->start_date?->format('Y-m-d') === $currentDay->format('Y-m-d');
                            });
                        @endphp

                        <div
                            class="min-h-[120px] bg-white dark:bg-gray-800 p-2 relative group">
                            <div class="flex justify-between items-center">
                                <span
                                    class="font-medium {{ $isToday ? 'text-blue-600 dark:text-blue-400' : ($isCurrentMonth ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600') }}">
                                    {{ $currentDay->format('j') }}
                                </span>
                                <button type="button" onclick="openModal('{{ $currentDay->format('Y-m-d') }}')"
                                    class="inline-flex items-center p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none">
                                    <span class="sr-only">Add activity</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>

                            @foreach($dayActivities as $activity)
                                <div class="mt-1">
                                    <div class="px-2 py-1 text-xs rounded-lg
                                                    @if($activity->type === 'meeting') bg-blue-100 text-blue-800
                                                    @elseif($activity->type === 'call') bg-green-100 text-green-800
                                                    @elseif($activity->type === 'task') bg-yellow-100 text-yellow-800
                                                    @else bg-purple-100 text-purple-800
                                                    @endif">
                                        {{ $activity->subject }}
                                        @if($activity->start_time)
                                            <span
                                                class="text-xs">{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @php
                            $currentDay->addDay();
                        @endphp
                    @endwhile
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden" style="z-index: 40;"></div>

    <!-- Add Activity Modal -->
    <div id="addActivityModal" class="fixed inset-0 flex items-center justify-center hidden" style="z-index: 50;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add Activity</h3>
                <button type="button" onclick="closeModal()"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <form id="addActivityForm">
                    @csrf
                    <input type="hidden" name="start_date" id="activityDate">
                    <input type="hidden" name="status" value="pending">
                    <input type="hidden" name="activityable_type" value="App\Models\User">
                    <input type="hidden" name="activityable_id" value="{{ auth()->id() }}">

                    <!-- Activity Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Activity
                            Type</label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select type</option>
                            <option value="meeting">📅 Meeting</option>
                            <option value="call">📞 Call</option>
                            <option value="task">✅ Task</option>
                            <option value="email">📧 Email</option>
                        </select>
                    </div>

                    <!-- Subject -->
                    <div class="mb-4">
                        <label for="subject"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <input type="text" name="subject" id="subject" required placeholder="Enter activity subject"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Time -->
                    <div class="mb-4">
                        <label for="start_time"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time</label>
                        <input type="time" name="start_time" id="start_time"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description
                            (Optional)</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Add any additional details"></textarea>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="mt-6 flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <button type="button" onclick="closeModal()"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('addActivityModal');
            const backdrop = document.getElementById('modalBackdrop');
            const form = document.getElementById('addActivityForm');
            const errorMessage = document.getElementById('errorMessage');

            // Function to navigate to a specific date
            function navigateToDate(date, view = 'month') {
                const url = `{{ route('calendar') }}?date=${date}&view=${view}`;
                window.location.href = url;
            }

            // Handle navigation button clicks
            document.querySelectorAll('[data-navigate]').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const date = button.dataset.date;
                    navigateToDate(date);
                });
            });

            window.openModal = function (date) {
                document.getElementById('activityDate').value = date;
                modal.classList.remove('hidden');
                backdrop.classList.remove('hidden');
                document.body.classList.add('modal-open');
            }

            window.closeModal = function () {
                modal.classList.add('hidden');
                backdrop.classList.add('hidden');
                document.body.classList.remove('modal-open');
                form.reset();
                errorMessage.classList.add('hidden');
            }

            // Close modal when clicking outside
            backdrop.addEventListener('click', closeModal);

            // Prevent clicks inside modal from closing it
            modal.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Form submission
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                // Disable submit button and show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = 'Adding...';

                // Clear previous errors
                errorMessage.classList.add('hidden');

                fetch('{{ route('calendar.activities.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect_url;
                        } else {
                            throw new Error(data.message || 'Failed to create activity');
                        }
                    })
                    .catch(error => {
                        errorMessage.textContent = error.message;
                        errorMessage.classList.remove('hidden');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    });
            });
        });
    </script>
@endsection