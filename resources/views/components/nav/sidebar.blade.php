@php

    // $currentRole =
    //     session('current_role') ?? Auth::user()->roles->pluck('name')->contains('Lecturer')
    //         ? 'Lecturer'
    //         : Auth::user()->roles->first()->name;

    $navLinks = match (session('current_role')) {
        'Admin' => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'lucide-layout-dashboard'],
            ['href' => route('departments'), 'text' => 'Departments', 'icon' => 'lucide-building'],
            ['href' => route('programmes'), 'text' => 'Programmes', 'icon' => 'lucide-graduation-cap'],
            ['href' => route('courses'), 'text' => 'Courses', 'icon' => 'lucide-book-open'],
            ['href' => route('course-allocations'), 'text' => 'Course Allocations', 'icon' => 'lucide-link'],
            ['href' => route('venues'), 'text' => 'Venues', 'icon' => 'lucide-map-pin'],
            ['href' => route('timetable'), 'text' => 'Timetable', 'icon' => 'lucide-calendar'],
            ['href' => route('timetable.generate'), 'text' => 'Generate Timetable', 'icon' => 'lucide-cpu'],
            ['href' => route('users'), 'text' => 'Users', 'icon' => 'lucide-users'],
            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'lucide-user'],
            ['href' => route('settings'), 'text' => 'Settings', 'icon' => 'lucide-settings'],
        ],
        default => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'lucide-layout-dashboard'],
            ['href' => route('timetable'), 'text' => 'My Timetable', 'icon' => 'lucide-calendar'],
            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'lucide-user'],
        ],
    };
@endphp

<aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100  shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0">
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700 relative">
        <div class="flex items-center space-x-2">
            <img class="size-12" src="{{ asset('assets/mzunilogo.webp') }}" alt="Mzuni logo">
            <p class="text-md font-medium text-gray-900 dark:text-white">ICT Weekend</p>
        </div>
        <button x-on:click="sidebarOpen = false"
            class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white absolute top-3 right-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation links -->
    <nav class="mt-5 px-4 space-y-1">
        @foreach ($navLinks as $link)
            <x-nav.nav-link :href="$link['href']" :icon="$link['icon']">
                {{ $link['text'] }}
            </x-nav.nav-link>
        @endforeach
    </nav>
</aside>
