<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <wireui:scripts />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles

</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-slate-800 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <img class="size-[100px]" src="{{ asset('assets/mzunilogo.webp') }}" alt="">
                </div>
                <h2 class="mt-3 text-center text-xl font-extrabold text-gray-900  dark:text-white">
                    {{ config('app.name') }}
                </h2>
                <p class="mt-2 text-center text-lg text-gray-600 dark:text-white">
                    {{ $title }}
                </p>
            </div>
            <div class="mt-4 bg-white  dark:bg-gray-900 p-4 drop-shadow-2xl rounded-2xl sm:px-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
