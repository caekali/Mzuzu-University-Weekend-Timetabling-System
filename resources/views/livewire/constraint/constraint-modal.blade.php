<x-modal-card id="constraint-modal" title="{{ $form->id ? 'Edit Constraint' : 'Add Constraint' }}" name="constraintModal">
    <form id="constraintForm" wire:submit.prevent="save" class="space-y-5">
        @if ($constraintable_type == 'lecturer')
            <x-select label="Lecturer" placeholder="Select Lecturer" :options="$constraintable_resources" option-label="name" option-value="id"
                wire:model.defer="form.constraintable_id" />
        @endif

        @if ($constraintable_type == 'venue')
            <x-select label="Venue" placeholder="Select Venue" :options="$constraintable_resources" option-label="name" option-value="id"
                wire:model.defer="form.constraintable_id" />
        @endif
        <x-select label="Day" placeholder="Select Day" :options="['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']" wire:model.defer="form.day" />
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-time-picker label="Start Time" name="form.start_time" military-time wire:model.live="form.start_time"
                without-seconds />
            <x-time-picker label="End Time" name="form.end_time" military-time wire:model.live="form.end_time"
                without-seconds />
        </div>
        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Constraint Type</p>
            <div class="flex gap-x-4">
                <x-radio id="type-unavailable" primary label="Unavailable" value="unavailable"
                    wire:model.defer="form.type" color="red" />
                <x-radio id="type-preferred" label="Preferred" value="preferred" wire:model.defer="form.type"
                    color="green" />
            </div>
        </div>
        <x-toggle label="Is Hard Constraint?" wire:model.defer="form.is_hard" on-label="Yes" off-label="No" />
    </form>
    
    <x-slot name="footer" class="flex justify-end gap-x-4">
        <x-button flat label="Cancel" x-on:click="close" />
        <x-button primary label="{{ $form->id ? 'Update' : 'Save' }}" type="submit" form="constraintForm" />
    </x-slot>
</x-modal-card>
