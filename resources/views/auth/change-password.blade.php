<x-layouts.guest subheader="Set New Password">
    <form class="space-y-4">
        <x-inputs.input label="New Password" name="password" type="password" id="password" required />
        <x-inputs.input label="Confirm Password" name="confirm-password" type="password" id="confirm-password" required />
        <div>
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed">
                @if (false)
                    <div class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
                    </div>
                @else
                    Change Password
                @endif
            </button>
        </div>
    </form>
</x-layouts.guest>
