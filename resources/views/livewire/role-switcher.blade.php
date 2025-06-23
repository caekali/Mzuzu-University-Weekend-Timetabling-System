<div>
    @foreach ($roles as $role)
        @if ($role !== $currentRole)
            {{-- <button wire:click="switchRole('{{ $role }}')"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 hover:dark:bg-gray-700">
                Switch to {{ ucfirst($role) }}
            </button> --}}

            <x-dropdown.item label="Switch to {{ ucfirst($role) }}" wire:click="switchRole('{{ $role }}')"/>
        @endif
    @endforeach
</div>
