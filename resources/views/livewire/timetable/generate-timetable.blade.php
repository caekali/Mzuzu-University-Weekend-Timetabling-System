<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white  mb-6">Generate Timetable</h1>
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <x-icons.sliders class="text-green-900 mr-2" />
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Algorithm Parameters</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <x-number label='Population Size' name='pupolation-size' />
                    <x-number label='Number of Generations' name='number-of-generations' />
                    <x-number label='Tournament Selection Size' name='tournament-selection-size' />
                </div>
                <div class="space-y-4">
                    <x-number label='Mutation Rate (%)' name='mutation-rate' />
                    <x-number label='Crossover Rate (%)' name='crossover-rate' />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-time-picker id="start-time" label="Start Time" placeholder="22:30" military-time
                            without-seconds />
                        <x-time-picker id="end-time" label="End Time" placeholder="22:30" military-time
                            without-seconds />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900   rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <x-icons.cpu class="mr-2 text-green-900" />
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Timetable Generation</h2>
            </div>

            @if ($isGenerating)
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Generation Progress</span>
                        <span>100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        {{-- Progress bar --}}
                    </div>
                </div>
            @endif

            @if ($showSuccess)
                <div class="mb-6 p-4 bg-green-50 rounded-md flex items-start">
                    <x-icon name="check" class="w-5 h-5" />
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Timetable generated successfully</h3>
                        <p class="mt-1 text-sm text-green-700">
                            Your timetable has been created. You can now view and adjust it as needed.
                        </p>
                    </div>
                </div>
            @endif

            {{--           
          {/* Conflicts section */}
          {conflicts.length > 0 && (
            <div class="mb-6 p-4 bg-amber-50 rounded-md">
              <div class="flex items-center mb-2">
                <AlertCircle class="h-5 w-5 text-amber-500 mr-2" />
                <h3 class="text-sm font-medium text-amber-800">Timetable generated with {conflicts.length} conflicts</h3>
              </div>
              <p class="text-sm text-amber-700 mb-2">
                The generated timetable has the following conflicts that need resolution:
              </p>
              <ul class="mt-2 space-y-1 list-disc list-inside text-sm text-amber-700 max-h-40 overflow-y-auto">
                {conflicts.slice(0, 5).map((conflict, index) => (
                  <li key={index}>{conflict.message}</li>
                ))}
                {conflicts.length > 5 && (
                  <li>...and {conflicts.length - 5} more</li>
                )}
              </ul>
            </div>
          )} --}}
            <div class="flex flex-col space-y-4">
                @if ($isGenerating)
                    <x-button label='Generating...' spinner />
                @else
                    <x-button icon='cpu-chip' label=' Generate Timetable' />
                @endif
                <x-button outline icon='calendar' label=" View Current Timetable" />
            </div>
        </div>
    </div>
</div>
