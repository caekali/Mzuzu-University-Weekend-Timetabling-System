<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Constraints</h1>
        <x-button icon="plus" label="Add Constraint" wire:click="openModal" primary />
    </div>
    <div
        class="bg-white p-6 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <x-table :headers="$headers" :rows="$constraints" :actions="true" :paginate="true" />
    </div>
    <livewire:constraint-modal />
</div>
