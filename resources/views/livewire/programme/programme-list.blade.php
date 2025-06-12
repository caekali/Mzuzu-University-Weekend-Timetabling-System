<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Programme</h1>
        <x-button icon="plus" label="Add Programme" wire:click="openModal" primary />
    </div>

    <div
        class="bg-white p-6 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="pb-4 w-64">
            <x-input placeholder="Search programme..." />
        </div>
        <div class="overflow-x-auto">
            @php
                $headers = [
                    'code' => 'Code',
                    'name' => 'Name',
                    'department' => 'Department',
                ];

                $rows = $programmes
                    ->map(function ($programme) {
                        return [
                            'id' => $programme->id,
                            'code' => $programme->code,
                            'name' => $programme->name,
                            'department' => $programme->department->name ?? 'N/A',
                        ];
                    })
                    ->toArray();
            @endphp
            <x-table :headers="$headers" :rows="$rows" :actions="true" :paginate="false" />
        </div>
    </div>
    <livewire:programme.programme-modal />
</div>
