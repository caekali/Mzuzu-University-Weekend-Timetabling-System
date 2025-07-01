@extends('layouts.guest')
@section('subtitle', 'Set New Password')

@section('content')
    <form class="space-y-4" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <x-password label="New Password" name="password" id="password" autoComplete="password" required :error="false" />
        <x-password label="Confirm Password" name="password_confirmation" id="password_confirmation"
            autoComplete="password_confirmation" required :error="false" />
        @session('status')
            <x-alert title="{{ session('status') }}" info />
        @endsession
        <x-button primary label='Reset Password' type='submit' class="w-full" />
    </form>
@endsection
