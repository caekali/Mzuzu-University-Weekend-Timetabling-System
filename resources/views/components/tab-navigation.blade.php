<div class="border-b border-gray-200">
    <nav class="-mb-px flex">
        @foreach ($tabs as $tab)
            <button wire:click="{{ $clickHandler }}('{{ $tab['value'] }}')"
                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm
                {{ $activeTab === $tab['value']
                    ? 'border-green-900 text-green-900'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                @if (isset($tab['icon']))
                    <x-dynamic-component :component="'lucide-' . $tab['icon']" class="h-4 w-4 inline mr-2" />
                @endif
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>
</div>
