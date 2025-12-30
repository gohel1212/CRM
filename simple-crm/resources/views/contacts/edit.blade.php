@extends('layouts.app')

@section('title', 'Edit Contact')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Contact</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700 p-6">
            <form action="{{ route('contacts.update', $contact) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                        <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $contact->customer_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $contact->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $contact->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm" required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $contact->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $contact->position) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                        <input type="text" name="department" id="department" value="{{ old('department', $contact->department) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center gap-3 mt-2">
                        <input type="checkbox" name="is_primary" id="is_primary" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }}>
                        <label for="is_primary" class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Contact</label>
                        @error('is_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 shadow-sm">{{ old('notes', $contact->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('contacts.index') }}" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">Cancel</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update Contact</button>
                </div>
            </form>
        </div>
    </div>
@endsection