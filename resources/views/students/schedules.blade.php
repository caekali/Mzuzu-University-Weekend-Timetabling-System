<x-layouts.dashboard>
<div class="flex flex-col h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg">
        <div class="flex items-center p-4 border-b">
            <img src="path_to_logo" alt="Mzuzu University Logo" class="h-8">
            <span class="ml-2 text-lg font-bold">Mzuzu University</span>
        </div>
        <nav class="mt-4">
            <ul>
                <li class="p-4 hover:bg-green-500 hover:text-white">
                    <a href="#">Dashboard</a>
                </li>
                <li class="p-4 bg-green-500 text-white">
                    <a href="#">My Schedule</a>
                </li>
                <li class="p-4 hover:bg-green-500 hover:text-white">
                    <a href="#">Profile</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow p-6">
        <header class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">My Schedule</h2>
            <div class="flex items-center">
                <span class="mr-2">Username</span>
                <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="ml-1">Role A</span>
            </div>
        </header>

        <div class="mt-4">
            <div class="grid grid-cols-7 gap-4">
                <div class="text-center font-semibold">Mon</div>
                <div class="text-center font-semibold">Tue</div>
                <div class="text-center font-semibold">Wed</div>
                <div class="text-center font-semibold">Thurs</div>
                <div class="text-center font-semibold">Fri</div>
                <div class="text-center font-semibold">Sat</div>
                <div class="text-center font-semibold">Sun</div>
            </div>

            <!-- Schedule Items -->
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
                        <strong>BICT1101 (End User Computing)</strong>
                    </div>
                    <p>ICT LAB 1</p>
                    <p>07:45 - 09:45</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-md shadow-md mb-4">
                    <div class="flex items-baseline">
                        <i class="fas fa-book mr-2"></i>
                        <strong>BICT1101 (End User Computing)</strong>
                    </div>
                    <p>ICT LAB 1</p>
                    <p>07:45 - 09:45</p>
                </div>
            </div>
        </div>
    </main>
</div>
</x-layouts.dashboard>
