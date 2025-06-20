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
                ]" active-tab="{{ $activeTab }}" />
            </div>

            @if ($activeTab === 'users')
                <div class="p-6">
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
                @livewire('user.user-csv-import')

                {{-- {uploadedUsers.length > 0 && (
              <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">Uploaded Users</h3>
                  <div class="flex space-x-3">
                    {!uploadedUsers[0]?.activationCode ? (
                      <button
                        onClick={processUsers}
                        disabled={isProcessing}
                        class="btn-primary disabled:opacity-50"
                      >
                        {isProcessing ? (
                          <>
                            <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></div>
                            Processing...
                          </>
                        ) : (
                          <>
                            <AlertCircle class="mr-2 h-4 w-4" />
                            Generate Activation Codes
                          </>
                        )}
                      </button>
                    ) : (
                      <button
                        onClick={sendInvitations}
                        disabled={isProcessing}
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 transition-all duration-200"
                      >
                        {isProcessing ? (
                          <>
                            <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></div>
                            Sending...
                          </>
                        ) : (
                          <>
                            <Send class="mr-2 h-4 w-4" />
                            Send Invitations
                          </>
                        )}
                      </button>
                    )}
                  </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activation Code</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                      {uploadedUsers.map((user, index) => (
                        <tr key={index} class="table-row">
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {user.firstName} {user.lastName}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {user.email}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                              {user.roles && getRoleBadges(user.roles)}
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {user.activationCode || '-'}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            )} --}}
        </div>
        @endif
    </div>
    <livewire:user.user-modal />
    </div>
