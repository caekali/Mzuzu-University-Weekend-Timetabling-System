{{-- @extends('layouts.guest')

@section('subtitle', 'Activate Account')

@section('content')
    <form class="space-y-4" method="POST" action="{{ route('auth.account.activation.send') }}">
        @csrf

        <x-input label="Email" name="email" type="email" id="name" placeholder="mail@my.mzuni.ac.mw" required />

        @error('status')
            <x-alert :title="$message" info />
        @enderror

        <x-button primary label='Send Activation Link' type='submit' class="w-full" />

        <div class="flex justify-center">
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route('login') }}">Back to
                Login</a>
        </div>
    </form>
@endsection --}}

<x-layouts.guest subheader='Activate Account'>
    @livewire('auth.request-activation-link')
</x-layouts.guest>
