 <x-modal-card id="user-modal" title="{{ $form->userId ? 'Edit User' : 'Add User' }}" name="userModal">
     <form id="userForm" wire:submit="save" class="space-y-4">
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <x-input label="First Name" placeholder="First Name" name="form.first_name" wire:model.defer="form.first_name"
                 required />
             <x-input label="Last Name" placeholder="Last Name" name="form.last_name" wire:model.defer="form.last_name"
                 required />
         </div>
         <x-input label="Email" placeholder="Email" name="form.email" wire:model="form.email" required />
         <x-select label="Roles" placeholder="Select user roles" :options="$roles" option-label="name"
             option-value="id" wire:model.live="userRoleIds" multiselect />

      
         @if ($this->hasRole('student'))
             <x-select label="Level" placeholder="Level" name="form.level" wire:model="form.level" :options="[1, 2, 3, 4, 5]"
                 required />

             <x-select label="Programme" placeholder="Select programme" :options="$programmes" option-label="name"
                 option-value="id" wire:model.defer="form.programme_id" />
         @endif

      
         @if ($this->hasRole('lecturer') || $this->hasRole('hod'))
             <x-select label="Department" placeholder="Select department" :options="$departments" option-label="name"
                 option-value="id" wire:model.defer="form.department_id" />
         @endif
     </form>
     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->userId ? 'Update' : 'Save' }}" type='submit' form='userForm' />
         </div>
     </x-slot>
 </x-modal-card>
