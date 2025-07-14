<x-layouts.empty>
    <div class="flex">
        <div class="mx-auto text-center">
            <img src="/error.png" width="300" class="mx-auto" />

            <div class="text-xl font-bold">Oops!</div>
            <div class="text-lg mx-auto max-w-4xl">{{ $message }}</div>

            <div class="flex mt-10">
                <div class="mx-auto grid md:grid-cols-2 gap-3">

                    <x-button :label="$isLivewire ? 'Close' : 'Reload'" :icon="$isLivewire ? 'o-x-mark' : 'o-arrow-path'" :onclick="$isLivewire
                        ? 'window.parent.document.getElementById(\'livewire-error\').remove()'
                        : 'window.location.reload()'" class="btn-primary" />

                    <x-button label="Home" icon="o-home" onclick="window.parent.location.href = '/'"
                        class="btn-outline" />
                </div>
            </div>
        </div>
    </div>
</x-layouts.empty>
