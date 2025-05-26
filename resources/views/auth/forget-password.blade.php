@extends('layouts.guest')
@section('subtitle', 'Forget Password')
<form class="space-y-4">
    <x-inputs.input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />
    @session('status')
        <p>{{ $message }}</p>
    @endsession
    <div>
        <button type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed">
            @if (false)
                <div class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
                </div>
            @else
                Send Password Reset Link
            @endif
        </button>
    </div>
    <div class="flex justify-center">
        <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
            Login</a>
    </div>
</form>
@endsection
