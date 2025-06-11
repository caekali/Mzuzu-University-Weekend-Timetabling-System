<x-slot:subheader>Reset Password</x-slot>

<form wire:submit.prevent="resetPassword" class="space-y-4">
    <x-password label="New Password" wire:model.defer="password" name="password" placeholder='Password' />

    <x-password label="Confirm Password" wire:model.defer="password_confirmation" name="password_confirmation" placeholder='Confirm Password' />

    @if ($errors->has('error'))
        <x-alert title="{{ $errors->first('error') }}"  negative />
    @endif

    <x-button type="submit" label="Reset Password" spinner="resetPassword" primary class="w-full" />
</form>
