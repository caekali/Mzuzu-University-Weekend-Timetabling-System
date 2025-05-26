@extends('layouts.guest')

@section('subtitle', 'Sign in')

@section('content')
    <form method="POST" class="space-y-4">
        @csrf
        <div>
            <label htmlFor="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autoComplete="email" value="{{ old('email') }}" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="mail@my.mzuni.ac.mw" />
            </div>
        </div>
        <div>
            <label htmlFor="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autoComplete="email" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="password" />
            </div>
        </div>

        @if ($errors->has('status'))
            <x-alert type="danger" :message="$errors->first('status')" class="mt-2" />
        @endif

        <div>
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline"
                href="{{ route('password.forget-password') }}">Forget
                password?</a>
        </div>
        <div>
            <x-button text='Sign in' type='submit' class="w-full" />
        </div>

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">Account not activated? <a href="{{ route('account-activation') }}"
                    class=" text-green-600 dark:text-green-500 hover:underline">activate</a></p>
        </div>
    </form>
@endsection
