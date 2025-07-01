<x-slot:subheader>Forgot Password</x-slot>
<div>
    <form wire:submit.prevent="sendResetLink" class="space-y-4">
        <x-input label="Email address" name="email" type="email" wire:model.defer="email"
            placeholder="you@my.mzuni.ac.mw" />

        @if (session('status'))
            <x-alert title="{{ session('status') }}" icon="check" info />
        @endif
        <x-button label="Send Reset Link" primary spinner="sendResetLink" type="submit" class="w-full" />
    </form>

    <div class="text-center text-sm mt-4">
        <x-link class="text-sm font-medium" label="Back to login" href="{{ route('login') }}" />
    </div>
</div>
