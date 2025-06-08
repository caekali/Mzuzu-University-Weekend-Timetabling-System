 <div class="p-6">
     <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">My Profile</h1>
     <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm">
         <div class="p-6">
             <div class="flex items-center mb-6">
                 <div
                     class="h-20 w-20 rounded-full bg-blue-900 flex items-center justify-center text-white text-2xl font-bold">
                     {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                 </div>
                 <div class="ml-6">
                     <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                         {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h2>
                     <p class="text-sm text-gray-500 capitalize">
                         {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                     </p>
                 </div>
             </div>

             <div class="space-y-4">
                 <div class="flex items-center text-gray-500">
                     <x-icons.email class="h-5 w-5 mr-3 text-gray-500  " />
                     <span>{{ auth()->user()->email }}</span>
                 </div>

                 @if (auth()->user()->hasRole('Student'))
                     <div class="flex items-center text-gray-500" x-data="{ editBtnClicked: false }">
                         <x-icons.open-book class="h-5 w-5 mr-3 text-gray-500" />
                         <span>BSc Information and Communication Technology - Level {{ $yearOfStudy ?? '1' }}</span>
                         <button x-show='!editBtnClicked' type="submit" @click="editBtnClicked = true"
                             class="ml-4 text-sm text-green-600 hover:text-green-700 hover:underline">Edit</button>

                         <form action="{{ route('profile') }}" method="POST" x-cloak
                             :class="editBtnClicked ? 'flex' : 'hidden'" class="ml-4 items-center space-x-2">
                             @csrf
                             @method('PUT')
                             <select name="year" class="rounded-md border-gray-300 text-sm">
                                 @for ($i = 1; $i <= 4; $i++)
                                     <option value="{{ $i }}"
                                         {{ isset($yearOfStudy) && $yearOfStudy == $i ? 'selected' : '' }}>
                                         Level {{ $i }}
                                     </option>
                                 @endfor
                             </select>
                             <button @click="editBtnClicked = false" type="submit"
                                 class="text-sm text-green-600 hover:text-green-700 hover:underline">Save</button>
                             <button @click="editBtnClicked = false" type="button"
                                 class="text-sm text-gray-600 hover:text-gray-700 hover:underline">Cancel</button>

                         </form>
                     </div>
                 @endif
                 @if (auth()->user()->hasRole('Lecturer'))
                     <div class="flex items-center text-gray-500">
                         <x-icons.building class="h-5 w-5 mr-3 text-gray-500" />
                         <span>Computer Science Department</span>
                     </div>
                 @endif
             </div>
             <div class="mt-8 pt-8 border-t">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change Password</h3>
                 <form wire:submit.prevent="update" class="space-y-4 max-w-md">
                     <x-input type="password" label="Current Password" wire:model.defer="form.current_password" />
                     <x-input type="password" label="New Password" wire:model.defer="form.password" />
                     <x-input type="password" label="Confirm Password" wire:model.defer="form.password_confirmation" />
                     <x-button type="submit" label="Update Password" spinner='update' icon='lock-closed' primary />
                 </form>
             </div>
         </div>
     </div>
 </div>
