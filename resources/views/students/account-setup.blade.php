<x-layouts.guest>
    <x-slot:subheader>
        Account Setup
    </x-slot:subheader>
    <form class="space-y-4">
        <x-inputs.select name="program" label="Programme" :options="[
            'ict' => 'ICT',
            'cs' => 'Computer Science',
            'se' => 'Software Engineering',
        ]" required />

        <x-inputs.select name="level" label="Level" :options="[
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7'
        ]" required />

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed">
                    @if (false)
                        <div class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
                        </div>
                    @else
                        Continue
                    @endif
                </button>
            </div>
    </form>
</x-layouts.guest>
