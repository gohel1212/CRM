<!-- Deals -->
<div class="mt-6">
    <div class="flex justify-between items-center">
        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Deals</h5>
        <a href="{{ route('deals.create', ['customer_id' => $customer->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Add Deal
        </a>
    </div>

    @if($customer->deals->count() > 0)
        <div class="mt-4 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($customer->deals as $deal)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $deal->name }}
                                </p>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $deal->status === 'won' ? 'bg-green-100 text-green-800' : 
                                       ($deal->status === 'lost' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($deal->status) }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('deals.show', $deal) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $deal->currency }} {{ number_format($deal->amount, 2) }}
                                @if($deal->expected_close_date)
                                    · Expected close: {{ $deal->expected_close_date->format('M d, Y') }}
                                @endif
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="mt-4 text-gray-500 dark:text-gray-400">No deals found.</p>
    @endif
</div> 