<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Welcome back, {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
            </p>
        </div>

        <x-button href="{{ route('timetable.generate') }}">
            <x-slot:prepend>
                <x-lucide-cpu class="h-4 w-4 mr-2" />
            </x-slot:prepend>
            Generate Timetable
        </x-button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach ($stats as $stat)
            <x-stat-card title="{{ $stat['title'] }}" value="{{ $stat['value'] }}" icon="{{ $stat['icon'] }}" />
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="flex items-center mb-4">
                <x-lucide-bar-chart-3 class="h-5 w-5 text-blue-900 dark:text-blue-400 mr-2" />
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">System Overview</h2>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <span>Course Allocation Progress</span>
                        <span>100%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            style={{ ' width:50%' }}></div>
                    </div>
                </div>
                <div class="pt-4">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Quick Stats</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Venues</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">0</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Active Users</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                0
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
{{-- 
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="flex items-center mb-4">
                <x-lucide-building class="h-5 w-5 text-blue-900 dark:text-blue-400 mr-2" />
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Department Overview</h2>
            </div>
            <div class="space-y-4">

                <div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-white mb-1">
                        <span>Ict</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            2 students • 3 lecturers
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            style={{ ' width:50%' }}></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-white mb-1">
                        <span>Ict</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            2 students • 3 lecturers
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            style={{ ' width:50%' }}></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-white mb-1">
                        <span>Ict</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            2 students • 3 lecturers
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            style={{ ' width:50%' }}></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-white mb-1">
                        <span>Ict</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            2 students • 3 lecturers
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            style={{ ' width:50%' }}></div>
                    </div>
                </div>

            </div>
        </div> --}}
    </div>


    {{-- quick actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('programmes') }}"
            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md dark:hover:shadow-lg transition-all duration-200 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-green-50 dark:bg-green-900/50 rounded-lg">
                    <x-lucide-building class="h-6 w-6 text-green-900 dark:text-green-400" />
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Programmes</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Manage academic programmes</p>
        </a>


        <a href="{{ route('courses') }}"
            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md dark:hover:shadow-lg transition-all duration-200 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-green-50 dark:bg-green-900/50 rounded-lg">
                    <x-lucide-users class="h-6 w-6 text-green-900 dark:text-green-400" />
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Courses</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Manage department courses</p>
        </a>


        <a href="{{ route('course-allocations') }}"
            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md dark:hover:shadow-lg transition-all duration-200 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-green-50 dark:bg-green-900/50 rounded-lg">
                    <x-lucide-map-pin class="h-6 w-6 text-green-900 dark:text-green-400" />
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Course Allocation</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Allocate course to lecturer</p>
        </a>
    </div>
</div>
