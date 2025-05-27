@props(['title' => 'Modal Title'])

<div x-cloak x-show="modalOpen" x-transition
    class="fixed inset-0 flex items-center justify-center bg-black/60 bg-opacity-50 z-50"
    @keydown.escape.window="modalOpen = false">
    <div class="bg-white w-full max-w-md p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ $title }}</h2>
            <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-500">
                <x-icons.close />
            </button>
        </div>
        <form action="">
            {{ $slot }}
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" @click="modalOpen = false"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{-- {editingDepartment ? 'Update Department' : 'Add Department'} --}}
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
