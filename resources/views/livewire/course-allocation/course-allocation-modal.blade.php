 <x-modal-card id="course-allocation-modal" title="{{ $form->allocationId ? 'Edit Allocation' : 'Add Allocation' }}"
     name="courseAllocationModal">
     <form id="courseAllocationForm" wire:submit="save" class="space-y-4">
         <x-select label="Course" placeholder="Select course" :options="$courses" option-label="name" option-value="id"
             wire:model.defer="form.course_id" />
         <x-select label="Lecturer" placeholder="Select lecturer" :options="$lecturers" option-label="name"
             option-value="id" wire:model.defer="form.lecturer_id" />
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <x-select label="Programmes" placeholder="Select programmes" :options="$programmes" option-label="name"
                 option-value="id" wire:model.defer="form.programme_ids" multiselect />
             <x-select class="w-10!" label="Level" placeholder="Level" name="form.level" wire:model="form.level"
                 :options="config('mzuni-config.levels')" />
         </div>
     </form>
     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->allocationId ? 'Update' : 'Save' }}" type='submit'
                 form='courseAllocationForm' />
         </div>
     </x-slot>
 </x-modal-card>
