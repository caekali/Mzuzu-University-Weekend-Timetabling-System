@props(['icon' => null, 'color' => 'primary', 'click' => null])

<x-button flat rounded :wire:click="$click"
    class="p-1.5 w-8 h-8 flex items-center justify-center text-{{ $color }}-600 dark:text-{{ $color }}-400">
    
    <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4" />
</x-button>
