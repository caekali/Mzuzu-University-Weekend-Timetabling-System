@props(['to', 'icon', 'title', 'description', 'color' => 'blue'])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
        'green' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
        'purple' => 'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700',
        'orange' => 'from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700',
        'indigo' => 'from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700',
        'red' => 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700',
        'teal' => 'from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700',
        'pink' => 'from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700',
    ];
    $gradient = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<a href="{{ $to }}"
    {{ $attributes->merge([
        'class' =>
            'group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-900 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 dark:border-gray-700',
    ]) }}>
    <div
        class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-0 group-hover:opacity-10 transition-opacity duration-300">
    </div>

    <div class="relative p-8">
        <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br {{ $gradient }} shadow-lg mb-6">
            <x-dynamic-component :component="'lucide-' . $icon" class="h-6 w-6 text-green-900 dark:text-green-400" />
        </div>
        <h3
            class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors duration-200">
            {{ $title }}
        </h3>

        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
            {{ $description }}
        </p>

        <div
            class="mt-6 flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-200">
            <span>Manage</span>
            <svg class="ml-2 h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>
</a>
