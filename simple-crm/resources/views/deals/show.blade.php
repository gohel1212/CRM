@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $deal->title }}</h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('deals.edit', $deal) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Deal
                </a>
                <a href="{{ route('deals.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Back to Deals
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">
            <!-- Deal Status Badge -->
            <div class="flex items-center">
                <span class="text-sm font-medium mr-2 text-gray-700 dark:text-gray-300">Status:</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($deal->status === 'won')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($deal->status === 'lost')
                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @else
                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @endif">
                    {{ ucfirst($deal->status) }}
                </span>
            </div>

            <!-- Deal Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Deal Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Value</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($deal->value, 2) }} {{ $deal->currency }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pipeline Stage</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $deal->stage->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expected Close Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $deal->expected_close_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $deal->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $deal->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Contact Information</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        @if($deal->contact)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('contacts.show', $deal->contact) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        {{ $deal->contact->name }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="mailto:{{ $deal->contact->email }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        {{ $deal->contact->email }}
                                    </a>
                                </dd>
                            </div>
                            @if($deal->contact->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <a href="tel:{{ $deal->contact->phone }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                            {{ $deal->contact->phone }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        @else
                            <div class="text-sm text-gray-500 dark:text-gray-400">No contact associated with this deal</div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Description -->
            @if($deal->description)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Description</h3>
                    <div class="prose dark:prose-invert max-w-none">
                        {{ $deal->description }}
                    </div>
                </div>
            @endif

            <!-- Activities -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Activities</h3>
                    <a href="{{ route('activities.create', ['deal_id' => $deal->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Activity
                    </a>
                </div>

                @if($deal->activities->count() > 0)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($deal->activities as $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                                    @if($activity->type === 'call')
                                                        bg-blue-500
                                                    @elseif($activity->type === 'meeting')
                                                        bg-green-500
                                                    @elseif($activity->type === 'email')
                                                        bg-yellow-500
                                                    @else
                                                        bg-gray-500
                                                    @endif">
                                                    @if($activity->type === 'call')
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                    @elseif($activity->type === 'meeting')
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                    @elseif($activity->type === 'email')
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ ucfirst($activity->type) }}
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $activity->created_at->format('M d, Y H:i') }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                    <p>{{ $activity->description }}</p>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('activities.edit', $activity) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No activities recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 