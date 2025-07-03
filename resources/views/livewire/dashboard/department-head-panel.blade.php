<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Head Of Department Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Welcome back, {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
            </p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach ($cards as $card)
            <x-action-card :to="$card['to']" :icon="$card['icon']" :title="$card['title']" :description="$card['description']" :color="$card['color']" />
        @endforeach
    </div>
</div>
