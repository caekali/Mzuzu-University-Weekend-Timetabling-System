<div class="py-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Course Allocations</h1>
        <div class="flex space-x-2">
            <x-button secondary wire:click="$toggle('showFilters')">
                <x-slot:prepend>
                    <x-lucide-filter class="h-4 w-4" />
                </x-slot:prepend>
                Filters
            </x-button>

            <x-button negative icon="trash" label="Clear All" wire:click="clearAll" />


            <x-button icon="plus" label="Add Allocation" wire:click="openModal" primary />

        </div>
    </div>

    <div x-data="{ open: @entangle('showFilters') }" x-show="open" x-cloak>
        <div
            class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 transition-colors duration-200 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Filters</h2>
                <button @click="open = false; $wire.set('showFilters', false)"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                    aria-label="Close filters">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-select label="Programme" placeholder="Select programme" :options="$programmes"
                    wire:model.live="selectedProgramme" option-label="name" option-value="id" />
                <x-select label="Level" placeholder="Select level" :options="config('mzuni-config.levels')" wire:model.live="selectedLevel" />
                <x-select label="Lecturer" placeholder="Select lecturer" :options="$lecturers" option-label="name"
                    option-value="id" wire:model.live="selectedLecturer" />
            </div>
        </div>
    </div>


    <div
        class="bg-white p-6 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="pb-4 w-64">
            <x-input wire:model.live="search" placeholder="Search course..." />
        </div>
        <x-table :headers="$headers" :rows="$allocations" :actions="true" :paginate="true" />
    </div>
    <livewire:course-allocation.course-allocation-modal />

</div>
