{{-- <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
            @foreach ($headers as $header)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ $header }}
                </th>
            @endforeach
            @if ($actions)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Actions
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $index => $row)
            <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                @foreach ($headers as $key => $header)
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                        {{ $row[$key] ?? '' }}
                    </td>
                @endforeach
                @if ($actions)
                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 space-x-2">
                        <button class="text-green-600 dark:text-green-400 hover:underline"
                            wire:click="openModal({{ $row['id'] }})">
                            Edit
                        </button>
                        <button class="text-red-600 dark:text-red-400 hover:underline delete-department"
                            wire:click="confirmDelete({{ $row['id'] }})">
                            Delete
                        </button>
                    </td>
                @endif
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


@if ($paginate && method_exists($rows, 'links'))
    <div class="mt-4">
        {{ $rows->links() }}
    </div>
@endif --}}


<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
            @foreach ($headers as $header)
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ $header }}
                </th>
            @endforeach
            @if ($actions ?? false)
                <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Actions
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $index => $row)
            <tr
                class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                @foreach ($headers as $key => $header)
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                        @if (isset($customCell) && is_callable($customCell))
                            {!! $customCell($row, $key) !!}
                        @else
                            {{ $row[$key] ?? '' }}
                        @endif
                    </td>
                @endforeach

                @if ($actions ?? false)
                    <td
                        class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 space-x-2">
                        <button class="text-green-600 dark:text-green-400 hover:underline"
                            wire:click="openModal({{ $row['id'] }})">
                            Edit
                        </button>
                        <button class="text-red-600 dark:text-red-400 hover:underline delete-button"
                            wire:click="confirmDelete({{ $row['id'] }})">
                            Delete
                        </button>
                    </td>
                @endif
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


@if ($paginate)
    <div class="mt-4">
        {{ $rows->links() }}
    </div>
@endif
