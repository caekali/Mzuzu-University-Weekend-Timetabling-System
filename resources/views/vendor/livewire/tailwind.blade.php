<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-4 mt-4">
            {{-- Pagination Status --}}
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

            {{-- Pagination Links --}}
            <div>
                <span class="relative z-0 inline-flex items-center rounded-md shadow-sm">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-2 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-l-md cursor-not-allowed dark:bg-gray-800 dark:border-gray-600">
                            &laquo;
                        </span>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="inline-flex items-center px-2 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-l-md hover:bg-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                            &laquo;
                        </button>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        {{-- Ellipsis --}}
                        @if (is_string($element))
                            <span class="inline-flex items-center px-4 py-2 text-sm text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Page Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 cursor-default leading-5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring ring-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:text-white dark:active:bg-gray-700 dark:focus:border-blue-700" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="inline-flex items-center px-2 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-r-md hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                            &raquo;
                        </button>
                    @else
                        <span class="inline-flex items-center px-2 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-r-md cursor-not-allowed dark:bg-gray-800 dark:border-gray-600">
                            &raquo;
                        </span>
                    @endif
                </span>
            </div>
        </nav>
    @endif
</div>
