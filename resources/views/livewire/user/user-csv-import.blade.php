  <div class="p-6">
      <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Upload CSV File
          </label>
          <div
              class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-gray-400 dark:hover:border-gray-500 transition-colors duration-200">
              <div class="space-y-1 text-center">
                  <x-lucide-upload class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                  <div class="flex text-sm text-gray-600 dark:text-gray-400">
                      <label htmlFor="file-upload"
                          class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-lg font-medium text-green-600 dark:text-green-400 hover:text-green-500 dark:hover:text-green-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 dark:focus-within:ring-offset-gray-900">
                          <span>Upload a file</span>
                          <form wire:submit.prevent="import">
                              <input type="file" wire:model="csvFile" accept=".csv" class="sr-only" />
                              @error('csvFile')
                                  <span class="text-red-500 text-sm">{{ $message }}</span>
                              @enderror
                          </form>
                      </label>
                      <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                      CSV with headers: first_name, last_name, email, role
                  </p>
              </div>
          </div>
      </div>

      <!-- Preview Table -->
      @if ($isReadyToImport && count($previewData))
          <div class="mt-6">
              <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">Uploaded Users</h3>
                  <div class="flex space-x-3">
                      <x-button wire:click="import" wire:loading.attr="disabled" label='Confirm & Import' />
                  </div>

                  <div wire:loading wire:target="import" class="flex items-center space-x-2 mt-4">
                      <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                          xmlns="http://www.w3.org/2000/svg">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                              stroke-width="4">
                          </circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                      </svg>
                      <span>Importing users...</span>
                  </div>


                  @if ($imported)
                      <div class="text-green-600 mt-4">
                          ✅ Successfully imported {{ $imported }} users.
                      </div>
                  @endif


                  @if ($failed)
                      <div class="text-red-600 mt-4">
                          <h3 class="font-bold">❌ Failed Rows:</h3>
                          <ul class="list-disc ml-5">
                              @foreach ($failed as $fail)
                                  <li>
                                      <strong>{{ $fail['data']['email'] ?? 'N/A' }}:</strong>
                                      {{ implode(', ', $fail['errors']) }}
                                  </li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
              </div>
              <div class="overflow-y-auto">
                  @php
                      $headers = [
                          'first_name' => 'First Name',
                          'last_name' => 'Last Name',
                          'email' => 'Email',
                      ];

                      $headers['role'] = 'Role';

                      $customCell = function ($row, $key) {
                          if ($key === 'role') {
                              $roleColors = [
                                  'Admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                  'Lecturer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                  'HOD' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                  'Student' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
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
                  <div class="overflow-x-auto">
                      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                          <thead class="bg-gray-100 dark:bg-gray-900">
                              <tr>
                                  @foreach ($headers as $header)
                                      <th
                                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                          {{ $header }}
                                      </th>
                                  @endforeach
                              </tr>
                          </thead>
                          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500">
                              @forelse($previewData as $index => $row)
                                  <tr class=" hover:bg-gray-100 dark:hover:bg-gray-700">
                                      @foreach ($headers as $key => $header)
                                          <td
                                              class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                              @if (isset($customCell) && is_callable($customCell))
                                                  {!! $customCell($row, $key) !!}
                                              @else
                                                  {{ $row[$key] ?? '' }}
                                              @endif
                                          </td>
                                      @endforeach

                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}"
                                          class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                          No data found
                                      </td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      @endif
  </div>
