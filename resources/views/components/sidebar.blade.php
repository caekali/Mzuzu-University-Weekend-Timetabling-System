@props(['isOpen' => false])

{{-- @php
    $navLinks = [
        [
            'href' => route('dashboard'),
            'text' => 'Dashboard',
            'icon' => 'icons.home',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'href' => route('courses.index'),
            'text' => 'Courses',
            'icon' => 'icons.book-open',
            'active' => request()->routeIs('courses.*'),
        ],
    ];
@endphp --}}

{{-- <div class="flex items-center justify-between h-16 px-6 border-b">
    <div class="flex items-center">
        <x-icons.cpu class="h-8 w-8 text-blue-900" />
        <span class="ml-2 text-xl font-bold text-blue-900">UniTime</span>
    </div>
</div>

<nav class="mt-5 px-4 space-y-1">
    @foreach ($navLinks as $link)
        <x-nav-link :href="$link['href']" :active="$link['active']" :icon="$link['icon']">
            {{ $link['text'] }}
        </x-nav-link>
    @endforeach

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-nav-link href="#" icon="icons.log-out" onclick="event.preventDefault(); this.closest('form').submit();">
            Logout
        </x-nav-link>
    </form>
</nav> --}}

<aside
    x-cloak
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen
    }"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out
           md:translate-x-0 md:static md:inset-0"
>
    <!-- Sidebar content -->
</aside>

