<div class="p-6 flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Programme</h1>
        <x-button icon="plus" label="Add Programme" wire:click="openModal" primary />
    </div>

    <div class="flex-1 grow bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="relative">
                <x-icons.search class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                <input type="text" placeholder="Search programme..."
                    class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>
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
