 <x-modal-card id="schedule-modal" title="{{ $form->scheduleEntryId ? 'Edit Schedule' : 'Add Schedule' }}"
     name="scheduleModal">
     <form id="scheduleForm" wire:submit="save" class="space-y-4">
         <x-select name='form.course_id' label="Course" placeholder="Select course" :options="$courses" option-label="name"
             option-value="id" wire:model.defer="form.course_id" />
         <x-select name='form.programme_id' label="Programme" placeholder="Select programme" :options="$programmes"
             option-label="name" option-value="id" wire:model.defer="form.programme_id" />

         <x-select name='form.lecturer_id"' label="Lecturer" placeholder="Select lecturer" :options="$lecturers"
             option-label="name" option-value="id" wire:model.defer="form.lecturer_id" />

         <x-select name='form.venue_id' label="Venue" placeholder="Select venue" :options="$venues" option-label="name"
             option-value="id" wire:model.defer="form.venue_id" />

     </form>
     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->scheduleEntryId ? 'Update' : 'Save' }}" type='submit'
                 form='scheduleForm' />
         </div>
     </x-slot>
 </x-modal-card>
