{{-- <x-slot:subheader>Activate Account</x-slot> --}}

<form wire:submit.prevent="sendActivationLink" class="space-y-4">
    <x-input label="Email" name="email" type="email" wire:model.defer="email" placeholder="mail@my.mzuni.ac.mw" required
        :error="$errors->first('email')" />

    @if ($errors->has('general'))
        <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
    @endif

    @if (session('status'))
        <x-alert icon="check" info title="{{ session('status') }}" />
    @endif

    <x-button primary label="Send Activation Link" spinner='sendActivationLink'
        wire:target="sendActivationLink" class="w-full" />
    <div class="text-center text-sm">
        <x-link label="Back to login" href="{{ route('login') }}" />
    </div>
</form>
