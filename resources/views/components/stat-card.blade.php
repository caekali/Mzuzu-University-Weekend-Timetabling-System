@props(['title' => '', 'value' => '', 'icon' => null])
<div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="p-2 bg-blue-50 dark:bg-green-900/50 rounded-lg">
                @if (isset($icon))
                    <x-dynamic-component :component="'lucide-' . $icon" class="h-6 w-6 text-green-900 dark:text-green-400" />
                @endif
                <Icon class="h-6 w-6 text-green-900 dark:text-green-400" />
            </div>
            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
        </div>
    </div>
    <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ $value }}</p>
</div>
