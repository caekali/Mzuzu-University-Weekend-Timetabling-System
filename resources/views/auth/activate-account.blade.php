@extends('layouts.guest')

@section('subtitle', 'Account Activation')

@section('content')
    <form class="space-y-4">
        <x-input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />
        <div>
            <x-button text='Submit' type='submit' class="w-full" />
        </div>
        <div class="flex justify-center">
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
                Login</a>
        </div>
    </form>
@endsection
