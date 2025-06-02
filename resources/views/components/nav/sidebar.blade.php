@php

    // $currentRole =
    //     session('current_role') ?? Auth::user()->roles->pluck('name')->contains('Lecturer')
    //         ? 'Lecturer'
    //         : Auth::user()->roles->first()->name;

    $navLinks = match (session('current_role')) {
        'Admin' => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard'],
            ['href' => route('departments.index'), 'text' => 'Departments', 'icon' => 'icons.database'],
            ['href' => route('programmes.index'), 'text' => 'Programmes', 'icon' => 'icons.open-book'],
            ['href' => route('courses.index'), 'text' => 'Courses', 'icon' => 'icons.open-book'],
            [
                'href' => route('users.index'),
                'text' => 'User',
                'children' => [
                    ['href' => route('users.index'), 'text' => 'All', 'icon' => 'icons.users'],
                    ['href' => route('users.students'), 'text' => 'Students', 'icon' => 'icons.graduation-cap'],
                    ['href' => route('users.lecturers'), 'text' => 'Lecturers', 'icon' => 'icons.lecturer'],
                ],
                'icon' => 'icons.users',
            ],
            ['href' => route('constraints.index'), 'text' => 'Constraints', 'icon' => 'icons.settings'],
            ['href' => route('venues.index'), 'text' => 'Venues', 'icon' => 'icons.building'],
            ['href' => route('timetable'), 'text' => 'Timetable', 'icon' => 'icons.calender'],
            ['href' => route('timetable.generate'), 'text' => 'Generate Timetable', 'icon' => 'icons.cpu'],

            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'icons.user'],
        ],
        default => [
            ['href' => route('dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard'],
            ['href' => route('timetable'), 'text' => 'Timetable', 'icon' => 'icons.calender'],
            ['href' => route('profile'), 'text' => 'Profile', 'icon' => 'icons.user'],
        ],
    };
@endphp



<aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0
">
    <div class="h-16 px-4 flex items-center justify-center">
        <div class="flex  justify-center">
            <img class="size-12" src="{{ asset('assets/mzunilogo.webp') }}" alt="Mzuni logo">
        </div>
        <p class="text-md text-center font-medium text-gray-900">Weekend Timetabling System</p>
    </div>
    <nav class="mt-5 px-4 space-y-1">
        @foreach ($navLinks as $link)
            @if (isset($link['children']))
                <x-nav.nav-group :routes="$link['children']" />
            @else
                <x-nav.nav-link :href="$link['href']" :active="request()->url() === $link['href']" :icon="$link['icon']">
                    {{ $link['text'] }}
                </x-nav.nav-link>
            @endif
        @endforeach
    </nav>


</aside>
