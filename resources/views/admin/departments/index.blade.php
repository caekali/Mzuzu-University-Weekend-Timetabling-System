 @extends('layouts.app')
 @section('content')
     <div class="p-6 flex flex-col">
         <div class="flex justify-between items-center mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Departments</h1>
             <x-button icon="plus" label="Add Department" x-on:click="$openModal('departmentModal')" primary />

             <x-modal-card title="Add Department" name="departmentModal">
                 <form id="departmentForm" action="{{ route('departments.store') }}" method="POST">
                    @csrf
                     <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                         <x-input label="Department Code" name='code' placeholder="Department Code"
                             value="{{ old('code') }}" required />
                         <x-input label="Department Name" name='name' placeholder="Department Name"
                             value="{{ old('name') }}" required />
                     </div>
                     <x-slot name="footer" class="flex justify-end gap-x-4">
                         <div class="flex gap-x-4">
                             <x-button flat label="Cancel" x-on:click="close" />
                             <x-button type='submit' primary label="Save" form="departmentForm" />
                         </div>
                     </x-slot>
                 </form>
             </x-modal-card>
             {{-- <div x-data="{ modalOpen: false }">
                 <x-button text='Add Department' icon='icons.plus' @click="modalOpen = true" />

                 <x-modal id="profileModal" title="Add Department">

                     <form action="{{ route('departments.store') }}" class="space-y-3" method="POST">
                         @csrf
                         <x-input label="Department Name" name='name' type='text' placeholder='Department name'
                             required />
                         <div class="flex justify-end space-x-3 pt-4 ">
                             <button type="button" @click="modalOpen = false"
                                 class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                 Cancel
                             </button>
                             <x-button type="submit" text='Add Department' class="cursor-default" />
                         </div>
                     </form>
                 </x-modal>
             </div> --}}
         </div>

         <div class="flex-1 grow bg-white rounded-lg shadow">
             <div class="p-4 border-b">
                 <div class="relative">
                     <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                     <input type="text" placeholder="Search department..."
                         class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                 </div>
             </div>
             <div class="overflow-x-auto">
                 @php
                     $headers = ['code' => 'Code', 'name' => 'Name'];
                 @endphp
                 <x-table :headers="$headers" :rows="$departments->toArray()" :actions="true" :paginate="false" />
                 <script>
                     document.addEventListener('DOMContentLoaded', function() {
                         document.querySelectorAll('.delete-department').forEach(button => {
                             button.addEventListener('click', function(e) {
                                 e.preventDefault();

                                 if (!confirm('Are you sure you want to delete this department?')) return;

                                 const departmentId = this.getAttribute('data-id');
                                 const row = this.closest('tr');

                                 fetch(`departments/${departmentId}`, {
                                         method: 'DELETE',
                                         headers: {
                                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                             'Accept': 'application/json',
                                         },
                                     })
                                     .then(response => {
                                         if (response.ok) {
                                             row.remove(); // Remove the row from the DOM
                                         } else {
                                             return response.json().then(data => {
                                                 alert(data.message ||
                                                     'Failed to delete department.');
                                             });
                                         }
                                     })
                                     .catch(error => {
                                         alert('An error occurred. Please try again.');
                                         console.error(error);
                                     });
                             });
                         });
                     });
                 </script>

             </div>
         </div>
     </div>
 @endsection
