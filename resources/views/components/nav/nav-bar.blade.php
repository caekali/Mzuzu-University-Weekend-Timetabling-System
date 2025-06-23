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
            <x-dropdown>
                <x-slot name="trigger">
                    <x-avatar sm :label="strtoupper(substr(Auth::user()->first_name, 0, 1)) .
                        strtoupper(substr(Auth::user()->last_name, 0, 1))" primary class='hover:cursor-pointer' />
                </x-slot>
                <livewire:role-switcher />
                <x-dropdown.item>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </x-dropdown.item>
            </x-dropdown>
        </div>
    </div>
</header>
