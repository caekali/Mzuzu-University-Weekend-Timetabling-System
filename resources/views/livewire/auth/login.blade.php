<x-slot:subheader>Sign in</x-slot>
<div>
    <form wire:submit.prevent="login" class="space-y-4">
        <x-input label="Email" name="email" type="email" wire:model="email" autocomplete="on"
            placeholder="you@my.mzuni.ac.mw" />

        <x-password label="Password" name="password" wire:model.defer="password" placeholder="Password" />

        <div class="flex justify-between items-center">
            <x-checkbox label="Remember Me" wire:model="remember" />
            <x-link class="text-sm font-medium" label="Forgot password?" href="{{ route('password.request') }}" />
        </div>

        @if ($errors->has('general'))
            <x-alert title="{{ $errors->first('general') }}" negative />
        @endif

        @if (session('status'))
            <x-alert title="{{ session('status') }}" icon="check" info />
        @endif

        <x-button primary label="Sign in" spinner='login' type="submit" class="w-full" />
    </form>
    <div class="mt-4 text-center text-sm">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-400">
            New here?
            <x-link class="text-sm font-medium" label="Activate account" href="{{ route('activation.request') }}" />
        </p>
    </div>
</div>
