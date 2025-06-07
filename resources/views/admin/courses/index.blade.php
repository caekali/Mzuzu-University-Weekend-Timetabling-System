 @extends('layouts.app')


 @section('content')
     <div class="p-6 flex flex-col">
         <div class="flex justify-between items-center mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Courses</h1>

             <x-button icon="plus" label="Add Course" x-on:click="$openModal('courseModal')" primary />

             <x-modal-card title="Add Course" name="courseModal">
                 <form id="courseForm" action="{{ route('courses.store') }}" method="POST">
                     @csrf
                     <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                         <x-input label="Course Code" placeholder="Course Code" name='code' required />
                         <x-input label="Course Name" placeholder="Course Name" name='name' required />

                         <div class="col-span-1 sm:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                             <x-number label="Level" placeholder="Level" name='level' required />
                             <x-number label="Semester" placeholder='Semester' name='semester' required />
                         </div>
                         <div class="col-span-1 sm:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                             <x-number label="Weekly Hours" placeholder="Weekly Hours" name='weekly_hours' required />
                             <x-number label="No. of students" placeholder='No. of students' name='num_of_students'
                                 required />
                         </div>
                         <x-select class="col-span-1 sm:col-span-2" label="Department" name='department_id'
                             placeholder="Select Department" :options="$departments"
                             required />
                     </div>
                 </form>
                 <x-slot name="footer" class="flex justify-end gap-x-4">
                     <div class="flex gap-x-4">
                         <x-button flat label="Cancel" x-on:click="close" />
                         <x-button primary label="Save" type='submit' form='courseForm' />
                     </div>
                 </x-slot>
             </x-modal-card>
         </div>

         <div class="flex-1 grow bg-white rounded-lg shadow">
             <div class="p-4 border-b">
                 <div class="relative">
                     <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                     <input type="text" placeholder="Search course..."
                         class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                 </div>
             </div>
             <div class="overflow-x-auto">

                 @php
                     $headers = [
                         'id' => 'ID',
                         'code' => 'Code',
                         'name' => 'Name',
                         'weeklyHours' => 'Weekly Hours',
                         'department' => 'Department',
                         'num_of_students' => '',
                     ];
                 @endphp
                 <x-table :headers="$headers" :rows="$courses->toArray()" :actions="true" :paginate="false" />
                 {{-- <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                         <tr>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Code
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Name
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Lecture Hours
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Department
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 No. of students
                             </th>
                             <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Actions
                             </th>
                         </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                         {filteredProgrammes.map(programme => (
                         <tr key={programme.id} class="hover:bg-gray-50">
                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                 {programme.code}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 {programme.name}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 {departments.find(d => d.id === programme.departmentId)?.name}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 {programme.degree}
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 {programme.duration} years
                             </td>
                             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                 <button onClick={()=> handleEditProgramme(programme)}
                                     class="text-blue-600 hover:text-blue-900 mr-4"
                                     >
                                     <Pencil class="h-4 w-4" />
                                 </button>
                                 <button onClick={()=> handleDeleteProgramme(programme.id)}
                                     class="text-red-600 hover:text-red-900"
                                     >
                                     <Trash2 class="h-4 w-4" />
                                 </button>
                             </td>
                         </tr>
                         ))}
                 </tbody>
                 </table> --}}
             </div>
         </div>
     </div>
 @endsection
