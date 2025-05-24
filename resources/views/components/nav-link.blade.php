@props(['href', 'active' => false, 'icon' => null])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => ($active ? 'bg-blue-50 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') . ' flex items-center px-4 py-2 text-sm font-medium rounded-md']) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" class="mr-3 h-5 w-5" />
    @endif
    {{ $slot }}
</a>
