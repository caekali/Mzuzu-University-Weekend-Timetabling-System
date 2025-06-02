@extends('layouts.guest')

@section('subtitle', 'Activate Account')

@section('content')
    <form class="space-y-4" method="POST" action="{{ route('auth.account.activation.send') }}">
        @csrf

        <x-input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.remove();">
                    <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                    </svg>
                </button>
            </div>
        @endif
        <div>
            <x-button text='Send Activation Link' type='submit' class="w-full" />
        </div>
        <div class="flex justify-center">
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
                Login</a>
        </div>
    </form>
@endsection
