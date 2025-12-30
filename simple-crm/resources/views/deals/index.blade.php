@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100">Deals</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('api.download.deals') }}"
                    style="background-color: #059669 !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-flex; align-items: center; font-weight: 600; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin-right: 0.5rem; width: 1rem; height: 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Deals
                </a>
                <a href="{{ route('deals.create') }}"
                    style="background-color: #2563eb !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-flex; align-items: center; font-weight: 600; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                    Create Deal
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Amount</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Expected Close</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($deals as $deal)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('deals.show', $deal) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $deal->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($deal->customer)
                                            <a href="{{ route('customers.show', $deal->customer) }}"
                                                class="text-gray-900 dark:text-gray-100">
                                                {{ $deal->customer->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">No Customer</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ $deal->currency }} {{ number_format($deal->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                {{ $deal->status === 'won' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                        ($deal->status === 'lost' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                            {{ ucfirst($deal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : 'Not set' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('deals.edit', $deal) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Are you sure you want to delete this deal?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                No deals found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $deals->links() }}
        </div>
    </div>
@endsection