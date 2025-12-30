@extends('layouts.app')

@section('title', 'Create Contact')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Create New Contact</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Add a new contact to your CRM system. Fields marked with * are required.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 space-y-6 sm:p-6">
                            <!-- Customer Selection -->
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Customer *
                                </label>
                                <select name="customer_id" id="customer_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('customer_id') border-red-500 @enderror" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        First Name *
                                    </label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('first_name') border-red-500 @enderror"
                                        required>
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Last Name *
                                    </label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('last_name') border-red-500 @enderror"
                                        required>
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email Address *
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('email') border-red-500 @enderror"
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Position -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Position
                                    </label>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('position') border-red-500 @enderror">
                                    @error('position')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Department
                                    </label>
                                    <input type="text" name="department" id="department" value="{{ old('department') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('department') border-red-500 @enderror">
                                    @error('department')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Primary Contact Checkbox -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded @error('is_primary') border-red-500 @enderror">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_primary" class="font-medium text-gray-700 dark:text-gray-300">Primary Contact</label>
                                    <p class="text-gray-500 dark:text-gray-400">Set this contact as the primary contact for the customer</p>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Notes
                                </label>
                                <div class="mt-1">
                                    <textarea id="notes" name="notes" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('notes') border-red-500 @enderror"
                                        placeholder="Add any additional notes about this contact">{{ old('notes') }}</textarea>
                                </div>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
                            <a href="{{ route('contacts.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Contact
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 