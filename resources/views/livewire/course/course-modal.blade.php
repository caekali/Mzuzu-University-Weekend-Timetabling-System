 <x-modal-card id="course-modal" title="{{ $form->courseId ? 'Edit Course' : 'Add Course' }}" name="courseModal">
     <form id="courseForm" wire:submit="save">
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <x-input label="Course Code" placeholder="Course Code" name="form.code" wire:model="form.code" />
             <x-input label="Course Name" placeholder="Course Name" name="form.name" wire:model="form.name" />

             <div class="col-span-1 sm:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                 <x-select label="Level" placeholder="Level" name="form.level" wire:model="form.level"
                     :options="config('mzuni-config.levels')" />
                 <x-select label="Semester" placeholder="Semester" name="form.semester" wire:model="form.semester"
                     :options="config('mzuni-config.semesters')" required />
             </div>
             <div class="col-span-1 sm:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                 <x-input label="Lecture Hours" placeholder="Lecture Hours" name="form.lecture_hours"
                     wire:model="form.lecture_hours" />
             </div>
             <x-select label="Department" name="form.department_id" wire:model="form.department_id"
                 placeholder="Select Department" :options="$departments->toArray()" option-label="name" option-value="id"
                 class="sm:col-span-2" />
         </div>
     </form>
     <x-slot name="footer" class="flex justify-end gap-x-4">
         <div class="flex gap-x-4">
             <x-button flat label="Cancel" x-on:click="close" />
             <x-button primary label="{{ $form->courseId ? 'Update' : 'Save' }}" type='submit' form='courseForm' />
         </div>
     </x-slot>
 </x-modal-card>
