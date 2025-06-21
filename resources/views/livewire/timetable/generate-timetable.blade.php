<div class="py-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white  mb-6">Generate Timetable</h1>

    <div class="grid grid-cols-1 gap-6">
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <form wire:submit.prevent="updateGAParameter">
                <div class="flex items-center mb-4">

                    <x-lucide-sliders class="text-green-900 mr-2 w-5 h-5" />
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Algorithm Parameters</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <x-number label='Population Size' name='form.population_size'
                            wire:model.live='form.population_size' />
                        <x-number label='Number of Generations' name='form.number_of_generations'
                            wire:model.live='form.number_of_generations' />
                        <x-number label='Tournament Selection Size' name='form.tournament_size'
                            wire:model.live='form.tournament_size' />
                    </div>
                    <div class="space-y-4">
                        <x-number label='Mutation Rate (%)' name='form.mutation_rate'
                            wire:model.live='form.mutation_rate' />
                        <x-number label='Crossover Rate (%)' name='form.crossover_rate'
                            wire:model.live='form.crossover_rate' />
                        <x-number label='Elite Schedules' name='form.elite_schedules'
                            wire:model.live='form.elite_schedules' />
                    </div>
                </div>
                <x-button type="submit" label="Update" class="mt-4" :disabled="!$this->hasChanges" wire:target="updateGAParameter"
                    wire:loading.attr="disabled" />
                @if ($lastUpdated)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Last updated: {{ \Illuminate\Support\Carbon::parse($lastUpdated)->format('Y-m-d H:i:s') }}
                    </p>
                @endif
            </form>

        </div>
        <div x-data="{ progress: {{ $progress }} }" @if ($isPolling) wire:poll.500ms="pollProgress" @endif
            x-effect="progress = {{ $progress }}"
            class="space-y-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <x-lucide-cpu class="w-5 h-5 text-green-900" />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Timetable Generation
                </h2>
            </div>
            <div>
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                    <p>Progress: <span x-text="Math.round(progress)"></span>%</p>
                    <p>Generation: {{ $currentGeneration }} / {{ $form->number_of_generations }}</p>
                    <p>Fitness: {{ $currentFitness }}</p>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-green-500 h-2.5 transition-all duration-500" :style="`width: ${progress}%`">
                    </div>
                </div>
            </div>
            @if ($isDone)
                <div class="p-4 bg-green-50 border border-green-200 rounded-md flex items-start gap-3">
                    <x-icon name="check" class="w-5 h-5 text-green-600 mt-1" />
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Timetable generated successfully</h3>
                        <p class="mt-1 text-sm text-green-700">
                            Your timetable has been created. You can now view and adjust it as needed.
                        </p>
                    </div>
                </div>
            @else
            @endif
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <x-button icon="cpu-chip" class="w-48" :label="$progress > 0 && $progress < 100 && !$isDone ? 'Generating...' : 'Generate Timetable'"
                    :disabled="$progress > 0 && $progress < 100 && !$isDone" x-on:click="$openModal('showVersionModal')"
                    wire:loading.attr="disabled" />
                <x-button href="{{ route('full.timetable') }}" outline icon="calendar" label="View Current Timetable" />
            </div>
        </div>
    </div>

    <x-modal-card title="Label Your Timetable Version" name="showVersionModal">
        <div class="space-y-4">
            <x-input label="Version Label" wire:model.live="versionLabel" placeholder="e.g. First Draft" />
        </div>
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <div class="flex gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save" wire:click="confirmGeneration" />
            </div>
        </x-slot>
    </x-modal-card>
</div>
