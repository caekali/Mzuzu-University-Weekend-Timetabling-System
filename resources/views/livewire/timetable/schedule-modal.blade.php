 <x-modal-card id="schedule-modal" title="{{ $form->scheduleEntryId ? 'Edit Schedule' : 'Add Schedule' }}"
     name="scheduleModal">
     <form id="scheduleForm" wire:submit="save" class="space-y-4">
         <x-select name='form.allocationId' label="Lecturer Allocation" placeholder="Select lecturer allocation"
             :options="$lecturerAllocations" option-label="label" option-value="id" wire:model.defer="form.allocationId" />

         {{-- <x-select name='form.programme_id' label="Programme" placeholder="Select programme" :options="$programmes"
             option-label="name" option-value="id" wire:model.defer="form.programme_id" /> --}}
         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6"> <x-select name='form.day' label="Day"
                 placeholder="Select day" :options="$days" wire:model.defer="form.day" />
             <x-time-picker label='Start Time' wire:model.live='form.start_time'
                 description='Start time of course sessions' military-time without-seconds />
         </div>
         <x-select name='form.venue_id' label="Venue" placeholder="Select venue" :options="$venues" option-label="name"
             option-value="id" wire:model.defer="form.venue_id" />
     </form>

     <x-slot name="footer">
         <div class="flex justify-between gap-x-4 items-center">
             @if ($form->scheduleEntryId)
                 <x-button flat negative label="Delete" wire:click="deleteEntries" />
             @else
                 <div></div>
             @endif
             <div class="flex gap-x-4">
                 <x-button flat label="Cancel" x-on:click="close" />
                 <x-button primary label="{{ $form->scheduleEntryId ? 'Update' : 'Save' }}" type="submit"
                     form="scheduleForm" />
             </div>
         </div>
     </x-slot>
 </x-modal-card>
