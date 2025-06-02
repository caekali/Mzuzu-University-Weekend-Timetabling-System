@extends('layouts.guest')

@section('subtitle', 'Sign in')

@section('content')
    <form method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autoComplete="email" value="{{ old('email') }}" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="mail@my.mzuni.ac.mw" />
            </div>
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autoComplete="passowrd" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="password" />
            </div>
        </div>


        @if (session('auth_error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('auth_error') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.remove();">
                    <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                    </svg>
                </button>
            </div>
        @endif


        <div>
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline"
                href="{{ route('password.request') }}">Forget
                password?</a>
        </div>
        <div>
            <x-button text='Sign in' type='submit' class="w-full" />
        </div>

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">
                New here? <a href="{{ route('auth.account.activation') }}"
                    class="text-green-600 dark:text-green-500 hover:underline">Activate account</a>.
            </p>

        </div>
    </form>
@endsection
