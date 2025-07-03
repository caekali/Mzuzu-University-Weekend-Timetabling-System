 {{-- <div class="py-6">
     <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">My Profile</h1>
     
     <div
         class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
         <div class="p-6">
             <div class="flex items-center mb-6">
                 <div
                     class="h-20 w-20 rounded-full bg-green-900 flex items-center justify-center text-white text-2xl font-bold">
                     {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                 </div>
                 <div class="ml-6">
                     <h2 class="text-xl font-bold text-gray-900 dark:text-gray-200">
                         {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h2>
                     <p class="text-sm text-gray-500 dark:text-gray-400">
                         {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                     </p>
                 </div>
             </div>

             <div class="space-y-4">
                 <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                     <x-lucide-mail class="h-5 w-5 mr-3 text-gray-500" />
                     <span>{{ auth()->user()->email }}</span>
                 </div>

                 @if (auth()->user()->hasRole('Student'))
                     <div class="flex items-center text-sm text-gray-500 dark:text-gray-400" x-data="{ editBtnClicked: false }">
                         <x-lucide-book-open class="h-5 w-5 mr-3 text-gray-500" />
                         <span>{{ auth()->user()->student->programme->name }} - Level
                             {{ auth()->user()->student->level }}</span>
                         <button x-show='!editBtnClicked' type="submit" @click="editBtnClicked = true"
                             class="ml-4 text-sm text-green-600 hover:text-green-700 hover:underline">Edit</button>

                         <form wire:submit.prevent="updateStudentLevel" x-cloak
                             :class="editBtnClicked ? 'flex' : 'hidden'" class="ml-4 items-center space-x-2">
                             @csrf
                             @method('PUT')
                             <select name="year" class="rounded-md border-gray-300 text-sm"
                                 wire:model='studentLevel'>
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
                     <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                         <x-lucide-building class="h-5 w-5 mr-3 text-gray-500" />
                         <span>{{ auth()->user()->lecturer->department->name }}</span>
                     </div>
                 @endif
             </div>
             <div class="mt-8 pt-8 border-t dark:border-t-gray-500">
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
 </div> --}}

 <div class="py-6 animate-fade-in">
     <div class="">
         <div class="flex items-center justify-between mb-6">
             <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
         </div>
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
             <div class="lg:col-span-2">
                 <div
                     class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
                     <div class="flex items-center justify-between mb-6">
                         <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h2>
                         <button wire:click="toggleEditProfile"
                             class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                             <x-lucide-pencil class="h-4 w-4 mr-2" />
                             {{ $isEditingProfile ? 'Cancel' : 'Edit Profile' }}
                         </button>
                     </div>

                     @if (!$isEditingProfile)
                         <div class="space-y-6">
                             <div class="flex items-center mb-6">
                                 <div
                                     class="h-20 w-20 rounded-full bg-gradient-to-br from-green-600 to-green-700 dark:from-green-500 dark:to-green-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                     {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                                 </div>
                                 <div class="ml-6">
                                     <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                         {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h2>
                                     </h3>

                                     <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                                         {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                                     </p>
                                 </div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 <div class="flex items-center text-gray-700 dark:text-gray-300">
                                     <x-lucide-mail class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500" />
                                     <div>
                                         <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                         <p class="font-medium">{{ auth()->user()->email }}</p>
                                     </div>
                                 </div>

                                 @if (session('current_role') === 'Student')
                                     <div class="flex items-center text-gray-700 dark:text-gray-300">
                                         <x-lucide-book-open class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500" />
                                         <div>
                                             <p class="text-sm text-gray-500 dark:text-gray-400">Programme</p>
                                             <p class="font-medium">{{ auth()->user()->student->programme->name }}</p>
                                         </div>
                                     </div>

                                     <div class="flex items-center text-gray-700 dark:text-gray-300">
                                         <x-lucide-graduation-cap
                                             class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500" />
                                         <div>
                                             <p class="text-sm text-gray-500 dark:text-gray-400">Level of Study</p>
                                             <p class="font-medium">Level {{ auth()->user()->student->level }}</p>
                                         </div>
                                     </div>

                                     <div class="flex items-center text-gray-700 dark:text-gray-300">
                                         <x-lucide-building class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500" />
                                         <div>
                                             <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                                             <p class="font-medium">{{ auth()->user()->student->programme->department->name}}</p>
                                         </div>
                                     </div>
                                 @elseif (session('current_role') === 'Lecturer')
                                     <div class="flex items-center text-gray-700 dark:text-gray-300">
                                         <x-lucide-building class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500" />
                                         <div>
                                             <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                                             <p class="font-medium">{{ auth()->user()->lecturer->department->name }}</p>
                                         </div>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     @else
                         <form wire:submit.prevent="updateProfile" class="space-y-6">
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 <x-input name="profileData.name" label="Full Name" required />
                                 <x-input name="profileData.email" label="Email Address" type="email" required />

                                 @if (session('current_role') === 'Student')
                                     <x-select name="profileData.programmeId" label="Programme" :options="$programmes"
                                         option-value="id" option-label="name" required />
                                     <x-select name="profileData.year" label="Year of Study" :options="[1, 2, 3, 4, 5, 6]"
                                         required />
                                 @elseif(session('current_role') === 'Lecturer')
                                     <x-select name="profileData.department" label="Department" :options="$departments"
                                         option-value="id" option-label="name" required />
                                 @endif
                             </div>

                             <div class="flex justify-end space-x-3">
                                 <x-button secondary wire:click.prevent="toggleEditProfile">Cancel</x-button>
                                 <x-button primary type="submit">
                                     <x-lucide-save class="h-4 w-4 mr-2" />Save Changes
                                 </x-button>
                             </div>
                         </form>
                     @endif
                 </div>
             </div>

             <div class="space-y-6">
                 <div
                     class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
                     <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Summary</h3>
                     <div class="space-y-4">
                         <div class="flex items-center justify-between">
                             <span class="text-sm text-gray-600 dark:text-gray-400">Role</span>
                             <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                 {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                             </span>
                         </div>
                         <div class="flex items-center justify-between">
                             <span class="text-sm text-gray-600 dark:text-gray-400">Member Since</span>
                             <span
                                 class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->created_at->format('M Y') }}</span>
                         </div>
                         <div class="flex items-center justify-between">
                             <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                             <span
                                 class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                 {{ auth()->user()->is_active ? 'Active' : 'Not Active' }}
                             </span>
                         </div>
                     </div>
                 </div>

                 <div
                     class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200 p-6">
                     <div class="flex items-center justify-between mb-4">
                         <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Security</h3>
                         <button wire:click="toggleEditPassword"
                             class="text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium transition-colors duration-200">
                             {{ $isEditingPassword ? 'Cancel' : 'Change Password' }}
                         </button>
                     </div>

                     @if (!$isEditingPassword)
                         <div class="space-y-3">
                             <div class="flex items-center text-gray-700 dark:text-gray-300">
                                 <x-lucide-lock class="h-4 w-4 mr-3 text-gray-400 dark:text-gray-500" />
                                 <p class="text-sm font-medium">Password</p>
                             </div>
                         </div>
                     @else
                         <form wire:submit.prevent="updatePassword" class="space-y-4">
                             <x-input type="password" label="Current Password"
                                 wire:model.defer="form.current_password" />
                             <x-input type="password" label="New Password" wire:model.defer="form.password" />
                             <x-input type="password" label="Confirm Password"
                                 wire:model.defer="form.password_confirmation" />
                             <x-button primary type="submit" class="w-full">
                                 <x-lucide-lock class="h-4 w-4 mr-2" />Update Password
                             </x-button>
                         </form>
                     @endif
                 </div>
             </div>
         </div>
     </div>
 </div>
