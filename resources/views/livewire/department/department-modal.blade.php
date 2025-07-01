 <x-modal-card id="department-modal" title="{{ $form->departmentId ? 'Edit Department' : 'Add Department' }}" name="departmentModal">
     <form id="departmentForm" wire:submit="save">
         @csrf
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <x-input label="Department Code" name='form.code' placeholder="Department Code" wire:model="form.code"
                 required />
             <x-input label="Department Name" name='form.name' placeholder="Department Name" wire:model="form.name"
                 required />
         </div>
         <x-slot name="footer" class="flex justify-end gap-x-4">
             <div class="flex gap-x-4">
                 <x-button flat label="Cancel" x-on:click="close" />
                 <x-button type='submit' primary label="{{ $form->departmentId ? 'Update' : 'Save' }}" form="departmentForm" />
             </div>
         </x-slot>
     </form>
 </x-modal-card>
