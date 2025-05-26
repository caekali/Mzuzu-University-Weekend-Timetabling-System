<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <img class="size-[100px]" src="{{ asset('assests/mzunilogo.webp') }}" alt="">
                </div>
                <h2 class="mt-3 text-center text-xl font-extrabold text-gray-900">
                   {{ config('app.name') }}
                </h2>
                <p class="mt-2 text-center text-lg text-gray-600">
                    @yield('subtitle')
                </p>
            </div>
            <div class="mt-4 bg-white p-4 shadow sm:rounded-lg sm:px-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
