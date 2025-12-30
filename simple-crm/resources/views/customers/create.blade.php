@extends('layouts.app')

@section('title', 'Add New Customer')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Add New Customer</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Create a new customer record in your CRM system. Fields marked with * are required.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 space-y-6 sm:p-6">
                            <!-- Basic Information -->
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address
                                        *</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone
                                        Number</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="website"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                            https://
                                        </span>
                                        <input type="text" name="website" id="website" value="{{ old('website') }}"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 @error('website') border-red-500 @enderror"
                                            placeholder="www.example.com">
                                    </div>
                                    @error('website')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Street
                                        Address</label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('address') border-red-500 @enderror">
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('city') border-red-500 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">State /
                                        Province</label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('state') border-red-500 @enderror">
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                    <label for="postal_code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP / Postal
                                        Code</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('postal_code') border-red-500 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="country"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                                    <input type="text" name="country" id="country" value="{{ old('country') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('country') border-red-500 @enderror">
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md @error('description') border-red-500 @enderror"
                                        placeholder="Add any additional notes about this customer">{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status *</label>
                                <select id="status" name="status" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
                            <a href="{{ route('customers.index') }}"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Customer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection