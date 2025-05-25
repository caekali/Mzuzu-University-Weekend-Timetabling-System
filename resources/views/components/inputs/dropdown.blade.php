@props(['align' => 'right', 'width' => '48'])

@php
    $alignmentClasses = [
        'left' => 'origin-top-left left-0',
        'right' => 'origin-top-right right-0',
    ];

    $widthClass = match($width) {
        '48' => 'w-48',
        default => 'w-48',
    };
@endphp

<div class="relative" x-data="{ open: false }">
    <div @click="open = !open" @keydown.escape.window="open = false">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition 
         @click.away="open = false"
         class="absolute z-50 mt-2 rounded-md shadow-lg {{ $alignmentClasses[$align] }} {{ $widthClass }} bg-white ring-1 ring-black ring-opacity-5"
         style="display: none;">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
