 @extends('layouts.app')


 @section('content')
     <div class="p-6 flex flex-col">
         <div class="flex justify-between items-center mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Courses</h1>
             <div x-data="{ modalOpen: false }">
                 <x-button text='Add Course' icon='icons.plus' @click="modalOpen = true" />

                 <x-modal id="profileModal" title="Add Course">
                     <x-input label="Course Name" name='course-name' type='text' placeholder='Course Name' required />
                     <x-input label="Course Code" name='course-code' type='text' placeholder='Course Code' required />
                     <div class="flex space-x-4">
                         <x-input label="Weekly Hours" name='weekly-hours' type='number' placeholder='Weekly Hours'
                             required />
                         <x-input label="No. of students" name='number-of-student' type='number'
                             placeholder='No. of students' required />
                     </div>
                     <x-input label="Department" name='department' type='text' placeholder='Department' required />

                 </x-modal>
             </div>
         </div>

         <div class="flex-1 grow bg-white rounded-lg shadow ">
             <div class="p-4 border-b">
                 <div class="relative">
                     <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                     <input type="text" placeholder="Search course..."
                         class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                 </div>
             </div>
             <div class="overflow-x-auto">
                 @php
                     $headers = ['id' => 'ID', 'name' => 'Name', 'email' => 'Email'];
                     $users = [
                         ['ID' => 1, 'Name' => 'Alice', 'Email' => 'alice@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com'],
                         ['ID' => 2, 'Name' => 'Bob', 'Email' => 'bob@example.com']
                     ];

                 @endphp
                 <x-table :headers="$headers" :rows="$users" :actions="true" :paginate="false" />
             </div>
         </div>
     </div>
 @endsection
