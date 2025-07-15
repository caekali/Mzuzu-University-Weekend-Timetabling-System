@props(['icon', 'title' => '', 'class' => ''])
<button {{ $attributes->merge(['class' => $class]) }} title="{{ $title }}">
    {{-- <x-icon name="{{ $icon }}" class="w-4 h-4" /> --}}
</button>
