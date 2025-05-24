<x-layouts.guest subheader="Sign in">
    <form method="POST" class="space-y-4">
        @csrf
        <div>
            <label htmlFor="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autoComplete="email" value="{{ old('email') }}"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="mail@my.mzuni.ac.mw" />
            </div>
        </div>
        <div>
            <label htmlFor="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autoComplete="email" required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    placeholder="password" />
            </div>
        </div>

        @if ($errors->has('status'))
            <x-alert type="danger" :message="$errors->first('status')" class="mt-2" />
        @endif

        <div>
            <a class="text-sm text-green-600 dark:text-green-500 hover:underline"
                href="{{ route('password.forget-password') }}">Forget
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

        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">Not registered? <a href="{{ route('contact.admin') }}"
                    class=" text-green-600 dark:text-green-500 hover:underline">Contact Admin</a></p>
        </div>
    </form>
</x-layouts.guest>
