 <x-modal-card id="venue-modal" title="{{ $form->venueId ? 'Edit Venue' : 'Add Venue' }}" name="venueModal">
     <form id="venueForm" wire:submit.prevent="save" class="space-y-4">
         <x-input label="Venue Name" placeholder="Venue name" name="name" wire:model.defer="form.name" required />
         <x-number label="Capacity" placeholder="Capacity" name="capacity" wire:model.defer="form.capacity" required />

         <x-checkbox label="Is this a lab?" name="is_lab" wire:model="form.is_lab" />
     </form>

     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->venueId ? 'Update' : 'Save' }}" type='submit' form='venueForm' />
         </div>
     </x-slot>
 </x-modal-card>
