<x-layouts.guest>
    <x-slot:subheader>
        Dashboard
    </x-slot:subheader>

    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-1/4 bg-white shadow-md">
            <div class="p-4">
                <h1 class="text-lg font-bold">Mzuzu University Weekend Timetabling System</h1>
            </div>
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-4 text-green-600 bg-green-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 hover:bg-gray-200 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"/>
                            </svg>
                            My Schedule
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 hover:bg-gray-200 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"/>
                            </svg>
                            Profile
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-8">
            <header class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Good Morning</h2>
                <div class="flex items-center">
                    <span class="mr-4">Username</span>
                    <div class="relative">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white">A</div>
                    </div>
                </div>
            </header>
            <section class="mt-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Next Class</h3>
                    <p>No more classes scheduled for today</p>
                    <a href="#" class="mt-4 text-green-600 underline">Go to Full Schedule →</a>
                </div>
            </section>
        </main>
    </div>
</x-layouts.guest>


