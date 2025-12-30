@extends('layouts.app')

@section('title', 'Edit Activity')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Activity</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('activities.update', $activity) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', $activity->subject) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">{{ old('description', $activity->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                                @php($type = old('type', $activity->type))
                                <option value="meeting" {{ $type === 'meeting' ? 'selected' : '' }}>📅 Meeting</option>
                                <option value="call" {{ $type === 'call' ? 'selected' : '' }}>📞 Call</option>
                                <option value="task" {{ $type === 'task' ? 'selected' : '' }}>✅ Task</option>
                                <option value="email" {{ $type === 'email' ? 'selected' : '' }}>✉️ Email</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                                @php($status = old('status', $activity->status))
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', optional($activity->start_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End date</label>
                            <input type="date" name="end_date" value="{{ old('end_date', optional($activity->end_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('activities.index') }}" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


