<div class="p-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Departments</h1>
        <x-button icon="plus" label="Add Department" wire:click="openModal" primary />
    </div>

    <div class="flex-1 grow bg-white dark:bg-gray-900 rounded-lg shadow">
        <div class="p-4 border-b dark:border-gray-700">
            <div class="relative">
                <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                <input
                    type="text"
                    placeholder="Search department..."
                    wire:model.debounce.300ms="search"
                    class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            @php
                $headers = ['code' => 'Code', 'name' => 'Name'];
                // $rows = $this->filteredDepartments->toArray(); // Use computed property
            @endphp
            <x-table :headers="$headers" :rows="$departments" :actions="true" :paginate="false" />
        </div>
    </div>


    <livewire:department.department-modal />
</div>
