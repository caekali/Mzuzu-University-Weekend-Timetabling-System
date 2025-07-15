<div class="space-y-6 py-6">
    @php
        function getRoleBadgeColor($role)
        {
            $roleColors = [
                'Admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                'Lecturer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'HOD' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'Student' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            ];

            return $roleColors[$role];
        }

    @endphp

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Users</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Manage user accounts and roles
            </p>
        </div>
        <x-button icon="plus" label="Add User" wire:click="openModal" primary />
    </div>

    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="border-b border-gray-200 ">
            <x-tab-navigation :tabs="[
                ['label' => 'Manage Users', 'value' => 'users', 'icon' => 'users'],
                ['label' => 'Bulk Import', 'value' => 'import', 'icon' => 'upload'],
            ]" activeTab="{{ $activeTab }}" />
        </div>
        @if ($activeTab === 'users')
            <div class="p-6" wire:key="users-tab">
                {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 flex-grow">
                    <x-input wire:model.live="search" placeholder="Search users..." />
                </div>
                <div class="w-48">
                    <x-select wire:model.live="userRoleFilter" placeholder="All Users" :options="$roles" />
                </div>
            </div>
        </div> --}}

                <div class="mb-6 space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1 ">
                            <x-input wire:model.live='search' placeholder="Search user..." />
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-48">
                                <x-select wire:model.live="userRoleFilter" placeholder="All Users" :options="$roles" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" overflow-x-auto mb-4">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <x-table.heading>User</x-table.heading>
                                <x-table.heading>Role</x-table.heading>
                                <x-table.heading>Status</x-table.heading>

                                @switch($userRoleFilter)
                                    @case('Student')
                                        <x-table.heading>Level</x-table.heading>
                                        <x-table.heading>Programme</x-table.heading>
                                    @break

                                    @case('Lecturer')
                                        <x-table.heading>Department</x-table.heading>
                                    @break

                                    @default
                                @endswitch
                                <x-table.heading align="center">Actions</x-table.heading>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 ">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600 dark:text-blue-200">
                                                    {{ collect(explode(' ', $user['name']))->map(fn($n) => strtoupper($n[0]))->join('') }}

                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $user['name'] }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $user['email'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class=" whitespace-nowrap">
                                        @foreach ($user['roles'] as $role)
                                            <span
                                                class='inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ getRoleBadgeColor($role) }}'>{{ $role }}</span>
                                        @endforeach
                                    </td>

                                    <td class=" whitespace-nowrap">
                                        <span
                                            class='inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user['status'] == 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}'>{{ $user['status'] }}</span>
                                    </td>

                                    @switch($userRoleFilter)
                                        @case('Student')
                                            <td
                                                class=" text-center text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                <span>{{ $user['level'] }}</span>
                                            </td>
                                            <td
                                                class="  text-left text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                <span>{{ $user['programme'] }}</span>
                                            </td>
                                        @break

                                        @case('Lecturer')
                                            <td
                                                class=" text-left text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                <span>{{ $user['department'] }}</span>
                                            </td>
                                        @break

                                        @default
                                    @endswitch
                                    <td class=" whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">

                                            <x-button flat rounded title="Edit User"
                                                wire:click="openModal({{ $user['id'] }})" class="p-1.5 w-8 h-8">
                                                <slot:prepend>
                                                    <x-lucide-pencil class="w-3 h-3 mr-1" />
                                                </slot:prepend>
                                            </x-button>
                                            @if ($user['status'] == 'Active')
                                                <x-button flat rounded title="Deactivate User"
                                                    wire:click="confirmUserDeactivation({{ $user['id'] }})"
                                                    class="p-1.5 w-8 h-8 flex items-center justify-center">
                                                    <slot:prepend>
                                                        <x-lucide-shield class="w-3 h-3 mr-1" />
                                                    </slot:prepend>
                                                </x-button>
                                            @else
                                                <x-button flat rounded negative title="Activate User"
                                                    wire:click="confirmUserActivation({{ $user['id'] }})"
                                                    class="p-1.5 w-8 h-8 flex items-center justify-center">
                                                    <slot:prepend>
                                                        <x-lucide-shield-off class="w-3 h-3 mr-1" />
                                                    </slot:prepend>
                                                </x-button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links('vendor.livewire.tailwind') }}
            </div>
        @else
            <div wire:key="import-tab">
                <livewire:user.user-csv-import />
            </div>
        @endif
        <livewire:user.user-modal />
    </div>
</div>
