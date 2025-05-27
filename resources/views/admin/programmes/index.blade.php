 @extends('layouts.app')


 @section('content')
     <div class="p-6 flex flex-col">
         <div class="flex justify-between items-center mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Programmes</h1>
             <div x-data="{ modalOpen: false }">
                 <x-button text='Add Programme' icon='icons.plus' @click="modalOpen = true" />

                 <x-modal id="profileModal" title="Add Programme">
                     <x-input label="Programme Code" name='programme-code' type='text' placeholder='Programme code'
                         required />
                     <x-input label="Programme Name" name='Programme Name' type='text' placeholder='Programme Name'
                         required />

                     <x-input label="Department" name='department' type='text' placeholder='Department'
                         required />

                 </x-modal>
             </div>
         </div>

         <div class="flex-1 grow bg-white rounded-lg shadow">
             <div class="p-4 border-b">
                 <div class="relative">
                     <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                     <input type="text" placeholder="Search programmes..."
                         class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                 </div>
             </div>
             <div class="overflow-x-auto">
                 <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                         <tr>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Code
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Name
                             </th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Department
                             </th>
                             <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Actions
                             </th>
                         </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200">
                         {{-- {filteredProgrammes.map(programme => (
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
                         ))} --}}
                     </tbody>
                 </table>
             </div>
         </div>
     </div>

     hvjhbhjhbjbhjbbbhbhbhhhbbh
 @endsection
