{{-- @props(['href', 'active' => false, 'icon' => null])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => ($active ? 'bg-green-50 text-green-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') . ' flex items-center px-4 py-2 text-sm font-medium rounded-md']) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" class="mr-3 h-5 w-5" />
    @endif
    {{ $slot }}
</a> --}}
@props(['href', 'icon' => null])

@php
    $baseClasses = 'flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200';
    $activeClasses = 'bg-green-50 text-green-900 dark:bg-green-900 dark:text-green-100';
    $inactiveClasses =
        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white';
@endphp

<a href="{{ $href }}" wire:navigate wire:current.exact="{{ $activeClasses }}"
    {{ $attributes->merge(['class' => $inactiveClasses . ' ' . $baseClasses]) }}>

    @if ($icon)
        <x-dynamic-component :component="$icon" class="mr-3 h-5 w-5" />
    @endif
    {{ $slot }}
</a>
