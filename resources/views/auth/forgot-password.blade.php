{{-- @extends('layouts.guest')
@section('subtitle', 'Forget Password')

@section('content')
    <form class="space-y-4" method="POST">
        @csrf
        <x-input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />
        @session('status')
            <x-alert title="{{ session('status') }}" info />
        @endsession


        <x-button primary label='Send Password Reset Link' type='submit' class="w-full" />

        <div class="flex justify-center">
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
                Login</a>
        </div>
    </form>
@endsection --}}

<x-layouts.guest subheader='Forget Password'>
    @livewire('auth.forgot-password')
</x-layouts.guest>
