@extends('layouts.guest')
@section('subtitle', 'Forget Password')
<form class="space-y-4">
    <x-inputs.input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />
    @session('status')
        <p>{{ $message }}</p>
    @endsession
    <div>
        <x-button text='Send Password Reset Link' type='submit' class="w-full" />
        </button>
    </div>
    <div class="flex justify-center">
        <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
            Login</a>
    </div>
</form>
@endsection
