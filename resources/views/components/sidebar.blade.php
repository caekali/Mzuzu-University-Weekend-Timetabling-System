@props(['isOpen' => false])

@php
    $role = session('current_role', Auth::user()->roles->first()->name);

    $navLinks = match ($role) {
        'Admin' => [
            ['href' => route('admin.dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard'],
        ],
        'Lecturer' => [
            ['href' => route('lecturer.dashboard'), 'text' => 'Dashboard', 'icon' => 'icons.layout-dashboard'],
            ['href' => route('lecturer.weekly-timetable'), 'text' => 'My Timetable', 'icon' => 'icons.calender']
        ],
        default => [],
    };
@endphp

<aside x-cloak :class="{
    'translate-x-0': sidebarOpen,
    '-translate-x-full': !sidebarOpen
}"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out
           md:translate-x-0 md:static md:inset-0">
    <nav class="mt-5 px-4 space-y-1">
        @foreach ($navLinks as $link)
            <x-nav-link :href="$link['href']" :active="request()->url() === $link['href']" :icon="$link['icon']">
                {{ $link['text'] }}
            </x-nav-link>
        @endforeach
    </nav>
</aside>
