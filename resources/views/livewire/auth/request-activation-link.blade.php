<x-slot:subheader>Activate Account</x-slot>
<form wire:submit.prevent="sendActivationLink" class="space-y-4">
    <x-input label="Email" name="email" type="email" wire:model.defer="email" placeholder="you@my.mzuni.ac.mw" autocomplete='email' />

    @if ($errors->has('general'))
        <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
    @endif

    @if (session('status'))
        <x-alert icon="check" info title="{{ session('status') }}" />
    @endif
    <x-button type="submit" primary label="Send Activation Link" spinner='sendActivationLink' class="w-full" />

    <div class="text-center text-sm">
        <x-link class="text-sm font-medium" label="Back to login" href="{{ route('login') }}" />
    </div>
</form>
