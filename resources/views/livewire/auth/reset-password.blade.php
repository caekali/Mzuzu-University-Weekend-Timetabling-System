<x-slot:subheader>Set New Password</x-slot>

<form wire:submit.prevent="resetPassword" class="space-y-4">
    <x-password label="New Password" wire:model.defer="password" name="password" required />

    <x-password label="Confirm Password" wire:model.defer="password_confirmation" name="password_confirmation" required />

    @if ($errors->has('general'))
        <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
    @endif

    @if (session('status'))
        <x-alert title="{{ session('status') }}" icon="check" info />
    @endif

    <x-button type="submit" label="Reset Password" spinner="resetPassword" primary class="w-full" />
</form>
