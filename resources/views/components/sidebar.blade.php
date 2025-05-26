@php

    // $currentRole =
    //     session('current_role') ?? Auth::user()->roles->pluck('name')->contains('Lecturer')
    //         ? 'Lecturer'
    //         : Auth::user()->roles->first()->name;

    $navLinks = match (session('current_role')) {
        'Admin' => [['href' => route('admin.dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard']],
        'Lecturer' => [
            ['href' => route('lecturer.dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard'],
            ['href' => route('lecturer.weekly-timetable'), 'text' => 'My Schedules', 'icon' => 'icons.calender'],
        ],
        default => [],
    };
@endphp

<aside x-cloak :class="{
    'block ': sidebarOpen,
    'hidden ': !sidebarOpen
}"
    class="hidden h-screen lg:block flex-1 flex-grow  z-30 w-64 bg-white shadow-lg  duration-300">

    <div class="h-16 px-4 flex items-center justify-center">
        <div class="flex  justify-center">
            <img class="size-12" src="{{ asset('assests/mzunilogo.webp') }}" alt="">
        </div>
        <p class="text-md text-center font-medium text-gray-900">ICTWTS</p>
    </div>
    <nav class="mt-5 px-4 space-y-1">
        @foreach ($navLinks as $link)
            <x-nav-link :href="$link['href']" :active="request()->url() === $link['href']" :icon="$link['icon']">
                {{ $link['text'] }}
            </x-nav-link>
        @endforeach
    </nav>
</aside>
