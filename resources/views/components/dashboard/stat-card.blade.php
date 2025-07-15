@props(['label', 'value', 'icon', 'color'])
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</p>
            <p class="text-2xl font-bold text-{{ $color }}-600">{{ $value }}</p>
        </div>
            <x-dynamic-component :component="'lucide-' . $icon" class="w-8 h-8 text-{{ $color }}-600"" />

    </div>
</div>