@props([
    'text' => 'button',
    'type' => '',
    'icon' => null,
])

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-900 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500']) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" />
    @endif
   <p>{{ $text}}</p>
</button>
