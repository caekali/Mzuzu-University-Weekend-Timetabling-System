@props([
    'text' => 'button',
    'type' => '',
    'icon' => null,
])

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => 'flex justify-center items-center  py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed']) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon"  class="mr-3 h-5 w-5"/>
    @endif
    <p>{{ $text }}</p>
</button>

{{-- <button type="submit"
    class="">
    @if (false)
        <div class="animate-spin h-5 w-5 border-t-2 border-b-2 border-white rounded-full">
        </div>
    @else
        Sign in
    @endif
</button> --}}
