@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            {{-- Small screens navigation --}}
            <div class="flex justify-between sm:hidden">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-default dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <button type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-default dark:bg-gray-800 dark:border-gray-600">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>

            {{-- Pagination info & links --}}
            <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
                {{-- Showing text --}}
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        {!! __('Showing') !!}
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

              
                <div class="flex flex-wrap">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @else
                        <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300  hover:text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif

                 
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white  border-gray-300  dark:bg-gray-800 dark:border-gray-600">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-green-600 border border-green-600  dark:bg-green-700">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300  hover:text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @else
                        <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
