<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <wireui:scripts />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" x-cloak></div>
        <x-nav.sidebar />
        <div class="flex-1 flex-grow  overflow-y-auto">
            <x-nav.nav-bar />
            <main class=" mx-auto max-w-7xl px-4 sm:px-6  bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
                {{ $slot }}
            </main>
        </div>
    </div>
    <x-modal />
    <x-notifications />
    <x-dialog />
    @wireUiScripts
    @livewireScripts
</body>

</html>
