@props([
    'label' => '',
    'name',
    'id' => $name,
    'options' => [],
    'selected' => null,
    'required' => false,
])

@php
    $selectedValue = old($name, $selected);
    $selectedText = $options[$selectedValue] ?? 'Select an option';
@endphp

<div x-data="{
    open: false,
    selectedText: @js($selectedText),
    selectedValue: @js($selectedValue)
}" class="relative w-full">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" x-ref="input" :value="selectedValue" {{ $required ? 'required' : '' }} />

    <button type="button" @click="open = !open" @keydown.escape="open = false"
        class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm">
        <span x-text="selectedText" class="block truncate"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 12a1 1 0 01-.707-.293l-3-3a1 1 0 111.414-1.414L10 9.586l2.293-2.293a1 1 0 111.414 1.414l-3 3A1 1 0 0110 12z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <ul x-show="open" x-cloak @click.away="open = false" x-transition
        class="absolute z-10 mt-1 w-full max-h-36 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
        @foreach ($options as $value => $display)
            <li @click="
                    selectedText = '{{ $display }}';
                    selectedValue = '{{ $value }}';
                    $refs.input.value = '{{ $value }}';
                    open = false
                "
                class="cursor-pointer select-none relative py-2 pl-10 pr-4 hover:bg-green-100 hover:text-green-900">
                <span class="block truncate">{{ $display }}</span>
            </li>
        @endforeach
    </ul>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
