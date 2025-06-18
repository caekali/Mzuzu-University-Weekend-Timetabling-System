<div class="py-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Course Allocations</h1>
        <x-button icon="plus" label="Add Allocation" wire:click="openModal" primary />
    </div>
    <div
        class="bg-white p-6 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        {{-- <div class="pb-4 w-64">
            <x-input wire:model.live="search" placeholder="Search Programme or Course..." />
        </div> --}}
        <x-table :headers="$headers" :rows="$allocations" :actions="true" :paginate="true" />
    </div>
    <livewire:course-allocation.course-allocation-modal />

</div>
