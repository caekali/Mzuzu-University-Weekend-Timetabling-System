{{-- @extends('layouts.guest')

@section('subtitle', 'Sign in')

@section('content')
    <livewire:courses.course-list />
    <div>
        <form method="POST" class="space-y-4">
            @csrf
            <x-input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw"
                autoComplete="email" value="{{ old('email') }}" required :error="false" />

            <x-password label="Password" name="password" type="password" id="password" placeholder="password"
                autoComplete="password" required />

            @if (session('status'))
                <x-alert title="{{ session('status') }}" info />
            @endif

            <div class="flex justify-between">
                <x-checkbox label='Remember Me' value='remember-me' primary />
                <x-link label="Forget
                    password?" href="{{ route('password.request') }}" />
            </div>
            <x-button primary label='Sign in' type='submit' class="w-full" />
        </form>
        <div class="mt-6 text-center text-sm">
            <p class="font-semibold text-gray-600 dark:text-white">
                New here?
                <x-link label="Activate account" href="{{ route('auth.account.activation') }}" />
            </p>
        </div>
    </div>
@endsection --}}


<x-layouts.guest subheader='Sign in'>
    @livewire('auth.login')
</x-layouts.guest>
