 <x-modal-card id="programme-modal" title="{{ $form->programmeId ? 'Edit Programme' : 'Add Programme' }}"
     name="programmeModal">
     <form id="programmeForm" wire:submit="save">
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <x-input label="Programme Code" placeholder="Programme Code" name="form.code" wire:model="form.code"
                  />
             <x-input label="Programme Name" placeholder="Programme Name" name="form.name" wire:model="form.name"
                  />
             <x-number label="No. of Students" placeholder="No. of Students" name="form.number_of_students"
                 wire:model="form.number_of_students" />
                 
             <x-select label="Department" name="form.department_id" wire:model="form.department_id"
                 placeholder="Select Department" :options="$departments->toArray()" option-label="name" option-value="id" />
         </div>
     </form>
     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->programmeId ? 'Update' : 'Save' }}" type='submit'
                 form='programmeForm' />
         </div>
     </x-slot>
 </x-modal-card>
