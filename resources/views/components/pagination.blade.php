@if ($paginator->hasPages())
<nav class="flex items-center justify-between text-sm" aria-label="Pagination">
    <div class="text-lunar-500 text-xs">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ number_format($paginator->total()) }}
    </div>
    <div class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-link opacity-40 cursor-not-allowed">‹ Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link">‹ Prev</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-lunar-600">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link">Next ›</a>
        @else
            <span class="pagination-link opacity-40 cursor-not-allowed">Next ›</span>
        @endif
    </div>
</nav>
@endif
