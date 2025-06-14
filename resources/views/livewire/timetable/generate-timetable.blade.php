<div class="py-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white  mb-6">Generate Timetable</h1>
    <div class="grid grid-cols-1 gap-6">
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="flex items-center mb-4">
                <x-lucide-sliders class="text-green-900 mr-2 w-5 h-5" />
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




        <div x-data="{ shown: {{ $progress }} }"
            @if (!$isDone) wire:poll.500ms="pollProgress" @else wire:init="pollProgress" @endif
            x-effect="shown = {{ $progress }} "
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">

            <div class="flex items-center mb-4">
                <x-lucide-cpu class="w-5 h-5 mr-2 text-green-900" />
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Timetable Generation</h2>
            </div>



            @if ($isDone)
                <div class="mb-6 p-4 bg-green-50 rounded-md flex items-start">
                    <x-icon name="check" class="w-5 h-5" />
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Timetable generated successfully</h3>
                        <p class="mt-1 text-sm text-green-700">
                            Your timetable has been created. You can now view and adjust it as needed.
                        </p>
                    </div>
                </div>
            @else
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <p class="mt-2 text-sm text-gray-700">
                            Generation Progress: <span x-text="Math.round(shown)"></span>%
                        </p>

                        <p>Running Generation {{ $currentGeneration }} / {{ $totalGenerations }}</p>
                        <p>Fitness: {{ $currentFitness }}</strong></p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded transition-all duration-300" :style="`width: ${shown}%`">
                        </div>
                    </div>
                </div>
            @endif
            <div class="flex flex-col space-y-4">
                <x-button icon="cpu-chip" :label="$progress > 0 && $progress < 100 ? 'Generating...' : 'Generate Timetable'" :spinner="$progress > 0 && $progress < 100"
                    :disabled="$progress > 0 && $progress < 100" wire:click="startGeneration"
                    wire:loading.attr="disabled" wire:target="startGeneration" />
                <x-button outline icon='calendar' label=" View Current Timetable" />
            </div>
        </div>
    </div>
</div>
