@extends('layouts.guest')

@section('subtitle', 'Set Your Password')

@section('content')
<form method="POST" action="{{ route('auth.account.activate', $user->id) }}">
    @csrf

    <label for="password" class="block mb-1">New Password</label>
    <input type="password" name="password" required class="w-full border rounded px-3 py-2">

    <label for="password_confirmation" class="block mt-4 mb-1">Confirm Password</label>
    <input type="password" name="password_confirmation" required class="w-full border rounded px-3 py-2">

    <x-button class="mt-4 w-full" text="Activate Account" type="submit" />
</form>
@endsection
