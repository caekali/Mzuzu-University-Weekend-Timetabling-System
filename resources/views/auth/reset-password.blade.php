@extends('layouts.guest')
@section('subtitle', 'Set New Password')

@section('content')
    <form class="space-y-4" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-input label="Email" name="email" type="email" id="email" required readonly
            value="{{ old('email', $request->email) }}" />

        <x-input label="New Password" name="password" type="password" id="password" required />
        <x-input label="Confirm Password" name="password_confirmation" type="password" id="password_confirmation" required />

       
        @session('status')
            <p>{{ session('status') }}</p>
        @endsession
        <div>
            <x-button text='Reset Password' type='submit' class="w-full" />
        </div>
         {{ dd($errors) }}
    </form>
@endsection
