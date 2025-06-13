<x-slot:subheader>Complete your {{ ucfirst($form->profileType) }} profile</x-slot>

<form wire:submit.prevent="save" class="space-y-4">
    @if ($form->profileType === 'student')
        <x-select label="Level" placeholder="Level" name="form.level" wire:model="form.level" :options="[1, 2, 3, 4, 5]" required />
        <x-select label="Programme" name="form.programme_id" wire:model="form.programme_id" placeholder="Select Programme"
            :options="$programmes" option-label="name" option-value="id" required />
    @elseif ($form->profileType === 'lecturer')
        <x-select label="Department" name="form.department_id" wire:model="form.department_id"
            placeholder="Select Department" :options="$departments" option-label="name" option-value="id" required />
    @endif

    @if ($errors->has('general'))
        <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
    @endif

    @if (session('status'))
        <x-alert icon="check" info title="{{ session('status') }}" />
    @endif
    <x-button primary type='submit' label="Submit" spinner='save' wire:target="save" class="w-full" />
</form>
