<div class="p-6">
    <div class="mb-6 bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
        <div class="flex items-start">
            <x-lucide-file-text class="h-5 w-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <h3 class="text-sm font-medium text-green-900 dark:text-green-300 mb-2">
                    CSV Format Requirements
                </h3>
                <div class="text-sm text-green-800 dark:text-green-400 space-y-2">
                    <p><strong>Required headers:</strong> first_name, last_name, email, role</p>
                    <p><strong>Optional headers:</strong> level (for students), programme (for students), department (for lecturers)</p>
                    <p><strong>Valid roles:</strong> student, lecturer, admin</p>
                    <p><strong>Student level:</strong> 1-4</p>
                    <p><strong>Valid programmes names or code from the system</strong></p>
                    <p><strong>Valid departments names or code from the system</strong></p>
                </div>
                <div class="mt-3">
                    <x-button secondary wire:click="downloadTemplate" wire:loading.attr="disabled"
                        label='Download Template'>
                        <x-slot:prepend>
                            <x-lucide-download class="h-4 w-4 mr-1" />
                        </x-slot:prepend>
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Upload CSV File
        </label>
        <div
            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-gray-400 dark:hover:border-gray-500 transition-colors duration-200">
            <div class="space-y-1 text-center">
                <x-lucide-upload class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                    <label for="file-upload"
                        class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-lg font-medium text-green-600 dark:text-green-400 hover:text-green-500 dark:hover:text-green-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 dark:focus-within:ring-offset-gray-900">
                        <span>Upload a file</span>
                        <form wire:submit.prevent="import">
                            <input id="file-upload" type="file" wire:model="csvFile" accept=".csv"
                                class="sr-only" />
                            @error('csvFile')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </form>
                    </label>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">CSV files only</p>
            </div>
        </div>
    </div>

    @if ($isReadyToImport && count($previewData))
        <div class="mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Import Preview ({{ count($previewData) }} users)
                </h3>
                <div class="flex space-x-3">
                    <x-button secondary wire:click="clearImports" wire:loading.attr="disabled" label='Clear'>
                        <x-slot:prepend>
                            <x-lucide-x class="h-4 w-4 mr-1" />
                        </x-slot:prepend>
                    </x-button>

                    <x-button wire:click="processUsers" wire:loading.attr="disabled" label='Process Import'>
                        <x-slot:prepend>
                            <x-lucide-send class="h-4 w-4 mr-2" />
                        </x-slot:prepend>
                    </x-button>
                </div>
            </div>

            @php
                $headers = [
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'role' => 'Role',
                    'level' => 'Level',
                    'programme' => 'Programme',
                    'department' => 'Department',
                ];

                $customCell = function ($row) {
                    $roleColors = [
                        'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                        'lecturer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                        'hod' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'student' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                    ];
                    $role = strtolower($row);
                    $classes = $roleColors[$role] ?? 'bg-gray-100 text-gray-800';
                    return "<span class='inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium $classes'>" . ucfirst($row) . "</span>";
                };
            @endphp

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 max-h-96">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                #
                            </th>
                            @foreach ($headers as $field => $label)
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach ($previewData as $row)
                            @php
                                $rowNum = $row['row_number'] ?? 'N/A';
                                $failedRow = collect($failed)->firstWhere('row_number', $rowNum);
                                $fieldErrors = $failedRow['field_errors'] ?? [];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $rowNum }}
                                </td>
                                @foreach ($headers as $field => $label)
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                        @if ($field === 'role')
                                            {!! $customCell($row['role']) !!}
                                        @else
                                            <span>{{ $row[$field] ?? '' }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @livewire('user.import-progress-modal')
</div>
