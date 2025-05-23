<x-layouts.guest>
    <x-slot:subheader>
        Dashboard
    </x-slot:subheader>

    <div class="flex flex-col md:flex-row">
        {{-- Sidebar --}}
        <aside class="w-full md:w-1/4 bg-white shadow-md md:h-screen">
            <div class="p-4">
                <h1 class="text-lg font-bold">Mzuzu University Weekend Timetabling System</h1>
            </div>
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-4 hover:bg-green-600 rounded-lg">
                            <i class="fas fa-th mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 hover:bg-green-600 rounded-lg">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            My Schedule
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-4 hover:bg-green-600 rounded-lg">
                            <i class="fas fa-user mr-2"></i>
                            Profile
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-4 md:p-8">
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
