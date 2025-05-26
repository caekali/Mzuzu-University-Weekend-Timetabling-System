@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h1>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="h-20 w-20 rounded-full bg-blue-900 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h2>
                        <p class="text-sm text-gray-500 capitalize">{{ auth()->user()->roles->pluck('name')->implode(', ') }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center text-gray-700">
                        <x-icons.email class="h-5 w-5 mr-3 text-gray-400" />
                        <span>{{ auth()->user()->email }}</span>
                    </div>

                    @if (auth()->user()->role === 'student')
                        <div class="flex items-center text-gray-700">
                            <x-icons.open-book class="h-5 w-5 mr-3 text-gray-400" />
                            <span>BSc Computer Science - Year {{ $yearOfStudy ?? '1' }}</span>

                            <form action="{{ route('profile') }}" method="POST" class="ml-4 flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="year" class="rounded-md border-gray-300 text-sm">
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}"
                                            {{ isset($yearOfStudy) && $yearOfStudy == $i ? 'selected' : '' }}>
                                            Year {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <button type="submit" class="text-sm text-green-600 hover:text-green-700">Save</button>
                            </form>
                        </div>
                    @endif

                    @if (auth()->user()->role === 'lecturer')
                        <div class="flex items-center text-gray-700">
                            <x-icons.building class="h-5 w-5 mr-3 text-gray-400" />
                            <span>Computer Science Department</span>
                        </div>
                    @endif
                </div>

                <div class="mt-8 pt-8 border-t">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>

                    <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4 max-w-md">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required />
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <x-icons.lock class="h-4 w-4 mr-2" />
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
