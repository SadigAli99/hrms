@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="text-sm text-slate-500">
            Showing
            <span class="font-semibold text-white">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-white">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-white">{{ $paginator->total() }}</span>
            results
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if ($paginator->onFirstPage())
                <span class="btn-ghost inline-flex items-center opacity-50 cursor-not-allowed" aria-disabled="true"
                    aria-label="@lang('pagination.previous')">
                    Prev
                </span>
            @else
                <a class="btn-ghost inline-flex items-center" onclick="filter({{ request('page', 1) - 1 }})"
                    rel="prev" aria-label="@lang('pagination.previous')">
                    Prev
                </a>
            @endif

            <div class="flex items-center gap-2 flex-wrap">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-sm text-slate-500">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-lg border border-brand-500/30 bg-brand-500/15 text-sm font-semibold text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <a class="icon-action-btn text-sm font-medium" onclick="filter({{ $page }})"
                                    aria-label="Go to page {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a class="btn-ghost inline-flex items-center" onclick="filter({{ request('page', 1) + 1 }})"
                    rel="next" aria-label="@lang('pagination.next')">
                    Next
                </a>
            @else
                <span class="btn-ghost inline-flex items-center opacity-50 cursor-not-allowed" aria-disabled="true"
                    aria-label="@lang('pagination.next')">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
