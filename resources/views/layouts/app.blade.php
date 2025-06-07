<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

            <wireui:scripts />

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    @livewireStyles

</head>

<body>
    <div class="h-screen flex bg-gray-50  dark:bg-slate-800" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/30 transition-opacity md:hidden" x-cloak></div>
        {{-- <x-nav.sidebar /> --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- <x-nav.nav-bar /> --}}
            <main @click="sidebarOpen = false" class="flex-1 overflow-y-auto bg-gray-50 p-4">
                <div class="max-w-7xl mx-auto flex-1 flex flex-col">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>


