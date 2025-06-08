<header class="bg-white dark:bg-slate-900 drop-shadow-2xl z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none md:hidden">
                    <x-icons.menu class="size-4" />
                </button>
            </div>

            <div class="flex items-center space-x-4">
                <!-- User Info + Role Switcher -->
                <div class="relative flex items-center">
                    <div class="hidden md:block text-right mr-3">
                        <div class="text-sm font-medium text-gray-900  dark:text-white">
                            {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                        </div>
                        <div class="text-xs text-gray-500 capitalize">
                            {{ session('current_role')}}
                        </div>
                    </div>

                    <!-- Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="h-8 w-8 rounded-full bg-blue-900 text-white font-medium flex items-center justify-center focus:outline-none">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" @click.outside="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" x-transition>
                            @foreach (Auth::user()->roles as $role)
                                @if ($role->name !== session('current_role'))
                                    <a href="{{ route('auth.switch-role', $role->name) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Switch to {{ ucfirst($role->name) }}
                                    </a>
                                @endif
                            @endforeach
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
        </div>
    </div>
</header>
