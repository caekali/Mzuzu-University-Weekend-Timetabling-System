    <div class="py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
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
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1 ">
                                <x-input placeholder="Search users..." />
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <x-select wire:model.live="userRoleFilter" placeholder="All Users"
                                        :options="$roles" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-y-auto">
                        @php
                            $headers = [
                                'id' => 'ID',
                                'name' => 'Name',
                                'email' => 'Email',
                            ];

                            if ($userRoleFilter) {
                                match (strtolower($userRoleFilter)) {
                                    'student' => ($headers += [
                                        'level' => 'Level',
                                        'programme' => 'Programme',
                                    ]),
                                    'lecturer' => ($headers += [
                                        'department' => 'Department',
                                    ]),
                                    default => null,
                                };
                            }

                            $headers['roles'] = 'Roles';

                            $customCell = function ($row, $key) {
                                if ($key === 'roles') {
                                    $roleColors = [
                                        'Admin' =>
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'Lecturer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'HOD' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'Student' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                    ];

                                    return collect(explode(',', $row[$key]))
                                        ->map(function ($role) use ($roleColors) {
                                            $role = trim($role);
                                            $label = $role === 'hod' ? 'HOD' : ucfirst($role);
                                            $classes = $roleColors[$role] ?? 'bg-gray-100 text-gray-800';
                                            return "<span class='inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mr-1 $classes'>$label</span>";
                                        })
                                        ->implode(' ');
                                }

                                return e($row[$key] ?? '');
                            };
                        @endphp

                        <x-table :headers="$headers" :rows="$users" :actions="true" :paginate="true"
                            :customCell="$customCell" />

                    </div>
                </div>
            @else
                <div wire:key="import-tab">
                    <livewire:user.user-csv-import />
                </div>
      
            @endif
        </div>


        <livewire:user.user-modal />
    </div>
