@php

    // $currentRole =
    //     session('current_role') ?? Auth::user()->roles->pluck('name')->contains('Lecturer')
    //         ? 'Lecturer'
    //         : Auth::user()->roles->first()->name;

    $navLinks = match (session('current_role')) {
        'Admin' => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'lucide-layout-dashboard'],
            ['href' => route('departments'), 'text' => 'Departments', 'icon' => 'lucide-database'],
            ['href' => route('programmes'), 'text' => 'Programmes', 'icon' => 'lucide-book-open'],
            ['href' => route('courses'), 'text' => 'Courses', 'icon' => 'lucide-book-open'],
           
            ['href' => route('venues'), 'text' => 'Venues', 'icon' => 'lucide-map-pin'],
            ['href' => route('timetable'), 'text' => 'Timetable', 'icon' => 'lucide-calendar'],
            ['href' => route('timetable.generate'), 'text' => 'Generate Timetable', 'icon' => 'lucide-cpu'],
            ['href' => route('users'), 'text' => 'Users', 'icon' => 'lucide-users'],
            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'lucide-user'],
        ],
        default => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'lucide-layout-dashboard'],
            ['href' => route('timetable'), 'text' => 'My Timetable', 'icon' => 'lucide-calendar'],
            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'lucide-user'],
        ],
    };
@endphp



<aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark: dark:bg-slate-900  shadow-lg  transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0
">
    <div class="h-16 px-4 flex items-center justify-center">
        <div class="flex  justify-center">
            <img class="size-12" src="{{ asset('assets/mzunilogo.webp') }}" alt="Mzuni logo">
        </div>
        <p class="text-md text-center font-medium text-gray-900  dark:text-white">Weekend Timetabling System</p>
    </div>
    <nav class="mt-5 px-4 space-y-1">
        @foreach ($navLinks as $link)
            <x-nav.nav-link :href="$link['href']" :icon="$link['icon']">
                {{ $link['text'] }}
            </x-nav.nav-link>
        @endforeach
    </nav>


</aside>
