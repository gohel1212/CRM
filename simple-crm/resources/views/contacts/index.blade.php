@extends('layouts.app')

@section('title', 'Contacts')

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('api.download.contacts') }}" 
           style="background-color: #059669 !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-flex; align-items: center; font-weight: 500; font-size: 0.875rem;"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
           onmouseover="this.style.backgroundColor='#047857'"
           onmouseout="this.style.backgroundColor='#059669'">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem; width: 1.25rem; height: 1.25rem; margin-left: -0.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Contacts
        </a>
        <a href="{{ route('contacts.create') }}" 
           style="background-color: #4f46e5 !important; color: white !important; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-flex; align-items: center; font-weight: 500; font-size: 0.875rem;"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
           onmouseover="this.style.backgroundColor='#4338ca'"
           onmouseout="this.style.backgroundColor='#4f46e5'">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem; width: 1.25rem; height: 1.25rem; margin-left: -0.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Contact
        </a>
    </div>
@endsection

@section('content')
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Name</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Customer</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Email</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Phone</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Position</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase">Primary</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($contacts as $contact)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100">
                                            <span class="text-sm font-medium leading-none text-indigo-700">
                                                {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $contact->first_name }} {{ $contact->last_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('customers.show', $contact->customer) }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                                    {{ $contact->customer->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $contact->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $contact->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $contact->position ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($contact->is_primary)
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('contacts.edit', $contact) }}" class="text-indigo-400 hover:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this contact?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
@endsection 