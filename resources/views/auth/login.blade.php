<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mzuzu University Weekend Timetabling System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <img class="size-[100px]" src="{{ asset('assests/mzunilogo.webp') }}" alt="">
                </div>
                <h2 class="mt-4 text-center text-xl font-extrabold text-gray-900">
                    Mzuzu University Weekend Timetabling System
                </h2>
                <p class="mt-2 text-center text-lg text-gray-600">
                    Sign in
                </p>
            </div>
            <div class="mt-4 bg-white p-4 shadow sm:rounded-lg sm:px-6">
                <form class="space-y-4">
                    <div>
                        <label htmlFor="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autoComplete="email" require
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="mail@my.mzuni.ac.mw" />
                        </div>
                    </div>
                    <div>
                        <label htmlFor="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autoComplete="email" require
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="password" />
                        </div>
                    </div>
                    <div>
                        <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route("password-assistance") }}">Forget
                            password?</a>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed">
                            @if (false)
                                <div class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
                                </div>
                            @else
                                Sign in
                            @endif
                        </button>
                    </div>
                    <div class="flex justify-center">
                        <a class="text-sm text-green-600 dark:text-green-500 hover:underline" href="{{ route("password-assistance") }}">Don’t have
                            password? Request one</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
