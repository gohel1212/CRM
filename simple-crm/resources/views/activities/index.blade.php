@extends('layouts.app')

@section('title', 'Activities')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 dark:bg-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Activities</h1>
            <a href="{{ route('activities.create') }}" 
               style="background-color: #2563eb !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.375rem; display: inline-flex; align-items: center; font-weight: 500; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-900 dark:border-gray-600"
               onmouseover="this.style.backgroundColor='#1d4ed8'"
               onmouseout="this.style.backgroundColor='#2563eb'">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-left: -0.25rem; margin-right: 0.5rem; width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Activity
            </a>
        </div>

        <!-- Activities List -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($activities as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->type === 'meeting') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($activity->type === 'call') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($activity->type === 'task') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @endif">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->subject }}</div>
                                    @if($activity->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($activity->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->start_date ? $activity->start_date->format('M d, Y') : 'No date set' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($activity->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @endif">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('activities.edit', $activity) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No activities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection 