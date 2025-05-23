<x-layouts.dashboard>
    <x-slot:subheader>
        Dashboard
    </x-slot:subheader>

    <div class="min-h-screen flex flex-col md:flex-row bg-gray-100 text-gray-800 font-sans">
        {{-- Sidebar --}}
        <aside class="w-full md:w-1/4 bg-white shadow-lg md:h-screen overflow-y-auto">
            <div class="p-4 text-center border-b">
                <div class="flex justify-center">
                    <img class="size-[100px]" src="{{ asset('assests/mzunilogo.webp') }}" alt="">
                </div>
                <h1 class="text-lg font-bold mt-2 leading-tight">Mzuzu University</h1>
                <p class="text-sm text-gray-600">Weekend Timetabling</p>
            </div>
            <nav class="mt-6 px-4">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-3 rounded-lg {{ request('view') === null ? 'bg-green-600 text-white' : 'hover:bg-green-600 hover:text-white' }}">
                            <i class="fas fa-th mr-2"></i> Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('dashboard', ['view' => 'schedule']) }}"
                            class="flex items-center p-3 rounded-lg {{ request('view') === 'schedule' ? 'bg-green-600 text-white' : 'hover:bg-green-600 hover:text-white' }}">
                            <i class="fas fa-calendar-alt mr-2"></i> My Schedule
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('dashboard', ['view' => 'profile']) }}"
                            class="flex items-center p-3 rounded-lg {{ request('view') === 'profile' ? 'bg-green-600 text-white' : 'hover:bg-green-600 hover:text-white' }}">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            <header class="mb-6 flex justify-between items-center border-b pb-4">
                <h2 class="text-2xl font-semibold">Good Morning</h2>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-700">{{ Auth::user()->name ?? 'Student' }}</span>
                    <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Conditional Content Display --}}
            @if (request('view') === 'schedule')
                {{-- SCHEDULE VIEW --}}
                <section>
                    <h3 class="text-2xl font-semibold mb-4">My Schedule</h3>
                    <div class="grid grid-cols-7 gap-4 text-center font-semibold">
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thur</div>
                        <div>Fri</div>
                        <div>Sat</div>
                        <div>Sun</div>
                    </div>

                    <div class="mt-4">
                        <div class="bg-yellow-100 p-4 rounded-md shadow-md mb-4">
                            <div class="flex items-baseline">
                                <i class="fas fa-book mr-2"></i>
                                <strong>BICT1101 (End User Computing)</strong>
                            </div>
                            <p>ICT LAB 1</p>
                            <p>07:45 - 09:45</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-md shadow-md mb-4">
                            <div class="flex items-baseline">
                                <i class="fas fa-book mr-2"></i>
                                <strong>BICT1102 (Introduction To C Programming)</strong>
                            </div>
                            <p>ICT LAB 1</p>
                            <p>08:45 - 10:45</p>
                        </div>
                    </div>
                </section>
            @elseif (request('view') === 'profile')
                {{-- PROFILE VIEW --}}
                <section>
                    <h3 class="text-2xl font-semibold mb-4">My Profile</h3>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold">{{ Auth::user()->name ?? 'Username' }}</h4>
                                <p class="text-gray-600">{{ Auth::user()->role ?? 'Student' }}</p>
                            </div>
                        </div>
                        <p>Email: {{ Auth::user()->email ?? 'student@mzuni.ac.mw' }}</p>
                        <p>Program: Bsc ICT - level 3</p>
                        <div class="mt-4 bg-white p-4 shadow sm:rounded-lg sm:px-6">
                            <form class="space-y-4">
                                <p class="mt-2 text-center text-lg text-gray-600">
                                    Change Password
                                </p>
                                <div>
                                    <label htmlFor="email" class="block text-sm font-medium text-gray-700">
                                        Current Password
                                    </label>
                                    <div class="mt-1">
                                        <input id="email" name="email" type="email" autoComplete="email" require
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="password" />
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="password" class="block text-sm font-medium text-gray-700">
                                        Password
                                    </label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password" autoComplete="email"
                                            require
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="password" />
                                    </div>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed">
                                        @if (false)
                                            <div
                                                class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
                                            </div>
                                        @else
                                            Update Passord
                                        @endif
                                    </button>
                                </div>

                            </form>
                        </div>
                </section>
            @else
                {{-- DEFAULT DASHBOARD SUMMARY --}}
                <section>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold">Next Class</h3>
                        <p class="text-gray-600 mt-2">No more classes scheduled for today</p>
                        <a href="{{ route('dashboard', ['view' => 'schedule']) }}"
                            class="mt-4 inline-block text-green-600 underline">
                            Go to Full Schedule →
                        </a>
                    </div>
                </section>
            @endif
        </main>
    </div>
</x-layouts.dashboard>
