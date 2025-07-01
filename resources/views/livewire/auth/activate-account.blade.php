<x-slot:subheader>Activate Account</x-slot>
<form wire:submit.prevent="activate" class="space-y-4">
    <x-password label="New Password" wire:model.defer="password" name="password" />

    <x-password label="Confirm Password" wire:model.defer="password_confirmation" name="password_confirmation" />

    @if ($errors->has('general'))
        <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
    @endif

    @if (session('status'))
        <x-alert title="{{ session('status') }}" icon="check" info />
    @endif

    <x-button type="submit" label="Activate Account" spinner="activate" primary class="w-full" />
</form>
