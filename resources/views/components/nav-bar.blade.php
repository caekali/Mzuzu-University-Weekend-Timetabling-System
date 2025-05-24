<header class="bg-white shadow-sm z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
               <button @click="sidebarOpen = !sidebarOpen" 
                    class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 md:hidden ">
                    <x-icons.menu class="size-4" />
                </button>
            </div>
            <div class="flex items-center">
                <button class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
                    <x-icons.bell class="size-5" />
                </button>
                <div class="ml-3 relative flex items-center">
                    <div class="flex items-center">
                        <div class="hidden md:block">
                            <div class="text-right mr-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</div>
                                <div class="text-xs text-gray-500 capitalize">
                                    {{ Auth::user()->roles()->first()->name }}</div>
                            </div>
                        </div>
                        <div class="ml-2 md:ml-0 flex-shrink-0">
                            <div
                                class="h-8 w-8 rounded-full bg-blue-900 flex items-center justify-center text-white font-medium">
                                {{ 'C' }}
                            </div>
                        </div>
              
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button title="logout" onClick="event.preventDefault(); this.closest('form').submit();"
                                class="ml-2 p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
                                <x-icons.logout class="size-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
