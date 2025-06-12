<div>
    @foreach ($roles as $role)
        @if ($role !== $currentRole)
            <button wire:click="switchRole('{{ $role }}')"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Switch to {{ ucfirst($role) }}
            </button>
        @endif
    @endforeach
</div>
