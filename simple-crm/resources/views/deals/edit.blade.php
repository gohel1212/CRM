@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100">Edit Deal</h1>
        <div class="flex space-x-4">
            <a href="{{ route('deals.show', $deal) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                View Deal
            </a>
            <a href="{{ route('deals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to Deals
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <form action="{{ route('deals.update', $deal) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deal Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $deal->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                    <select name="customer_id" id="customer_id"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $deal->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $deal->amount) }}" step="0.01" required
                            class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <select name="currency" id="currency"
                            class="rounded-r-md border-l-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="USD" {{ old('currency', $deal->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency', $deal->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ old('currency', $deal->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                        </select>
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                

                <div>
                    <label for="expected_close_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Close Date</label>
                    <input type="date" name="expected_close_date" id="expected_close_date" 
                        value="{{ old('expected_close_date', $deal->expected_close_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('expected_close_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $deal->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update Deal
                </button>
            </div>
        </form>
    </div>

</div>
@endsection 