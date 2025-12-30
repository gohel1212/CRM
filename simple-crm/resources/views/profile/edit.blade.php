@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Profile Settings</h2>

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 font-bold mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full px-3 py-2 border rounded-lg @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">New Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 