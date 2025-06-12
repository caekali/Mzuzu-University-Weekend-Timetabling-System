@props(['subheader' => ''])

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
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <img class="size-[100px]" src="{{ asset('assets/mzunilogo.webp') }}" alt="">
                </div>
                <h2 class="mt-3 text-center text-xl font-extrabold text-gray-900  dark:text-white">
                    {{ config('app.name') }}
                </h2>
                <p class="mt-2 text-center text-lg text-gray-600 dark:text-gray-400">
                    {{ $subheader }}
                </p>
            </div>
            <div class="mt-4 bg-white dark:bg-gray-800 py-8 px-4 transition-colors duration-200 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-800">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
