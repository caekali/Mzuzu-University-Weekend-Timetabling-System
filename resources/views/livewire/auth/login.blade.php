<div>
    <form wire:submit.prevent="login" class="space-y-4">
        <x-input label="Email" name="email" type="email" wire:model.defer="email" placeholder="mail@my.mzuni.ac.mw"
            required :error="$errors->first('email')" />

        <x-password label="Password" name="password" wire:model.defer="password" placeholder="Password" required
            :error="$errors->first('password')" />

        <div class="flex justify-between items-center">
            <x-checkbox label="Remember Me" wire:model="remember" />
            <x-link label="Forgot password?" href="{{ route('password.request') }}" />
        </div>

        @if ($errors->has('general'))
            <x-alert title="{{ $errors->first('general') }}" icon="error" negative />
        @endif

        @if (session('status'))
            <x-alert title="{{ session('status') }}" icon="check" info />
        @endif

        <x-button primary label="Sign in" spinner='login' type="submit" class="w-full" />
    </form>
    <div class="mt-4 text-center text-sm">
        <p class="font-semibold text-gray-600 dark:text-white">
            New here?
            <x-link label="Activate account" href="{{ route('activation.request') }}" />
        </p>
    </div>
</div>
