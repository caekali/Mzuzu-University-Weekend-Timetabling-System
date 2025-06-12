<header
    class="sticky top-0 flex justify-between  bg-white dark:bg-gray-800 shadow-md z-10 transition-colors duration-200 text-slate-900 dark:text-slate-100 h-16 items-center gap-x-4  px-4 sm:px-6 lg:px-8 ">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-600 dark:focus:text-gray-300 md:hidden transition-colors duration-200">
            <x-lucide-menu class="size-6" />
        </button>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative flex items-center">
            <div class="hidden md:block text-right mr-3">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                    {{ session('current_role') }}
                </div>
            </div>
            <div x-data="{ open: false }" @close-dropdown.window="open = false" class="relative">
                <button @click="open = !open"
                    class="h-8 w-8 rounded-full bg-blue-900 text-white font-medium flex items-center justify-center focus:outline-none">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                </button>
                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" x-transition>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
  {{-- <livewire:role-switcher /> --}}
