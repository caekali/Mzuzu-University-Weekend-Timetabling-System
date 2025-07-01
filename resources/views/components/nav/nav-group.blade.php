<!-- resources/views/components/nav/user-group.blade.php -->
@props([
    'title' => 'Users',
    'icon' => 'users',
    'expanded' => false,
    'routes' => [],
])

<div x-data="{ usersExpanded: {{ $expanded ? 'true' : 'false' }} }">
    <!-- Toggle button -->
    <button x-cloak @click="usersExpanded = !usersExpanded"
        class="w-full flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
        <x-dynamic-component :component="'icons.' . $icon" class="mr-3 h-5 w-5" />
        <span>{{ $title }}</span>
        <x-icons.chevron-down x-show="usersExpanded" class="ml-auto h-4 w-4" />
        <x-icons.chevron-right x-show="!usersExpanded" class="ml-auto h-4 w-4" />
    </button>

    <!-- Expanded content -->
    <div x-show="usersExpanded" class="ml-4 mt-1 space-y-1" x-transition>
        @foreach ($routes as $route)
            <x-nav.nav-link :href="$route['href']" :active="request()->url() === $route['href']" :icon="$route['icon']">
                {{ $route['text'] }}
            </x-nav.nav-link>
        @endforeach
    </div>
</div>
