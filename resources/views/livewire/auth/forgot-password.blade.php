<div class="max-w-md mx-auto space-y-6">
    <form wire:submit.prevent="sendResetLink" class="space-y-4">
        @csrf

        <x-input label="Email address" name="email" type="email" wire:model.defer="email" placeholder="you@example.com"
            :error="$errors->first('email')" required />

        @if (session('status'))
            <x-alert title="{{ session('status') }}" icon="check" info />
        @endif

        <x-button label="Send Reset Link" primary type="submit" class="w-full" />
    </form>

    <div class="text-center text-sm">
        <x-link label="Back to login" href="{{ route('login') }}" />
    </div>
</div>
