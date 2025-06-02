<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            @foreach ($headers as $header)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ $header }}</th>
            @endforeach
            @if ($actions)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $index => $row)
            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                @foreach ($headers as $key => $header)
                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                        {{ $row[$key] ?? '' }}
                    </td>
                    @if ($actions)
                        <td class="px-6  whitespace-nowrap text-sm font-medium text-gray-900 space-x-2">
                            <a href="" class="text-blue-600 hover:underline">Edit</a>
                            <button class="text-red-600 hover:underline delete-department" data-id="{{ $row['id'] }}">
                                Delete
                            </button>
                        </td>
                    @endif
                @endforeach

            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-4 text-sm text-gray-500">
                    No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


@if ($paginate && method_exists($rows, 'links'))
    <div class="mt-4">
        {{ $rows->links() }}
    </div>
@endif
