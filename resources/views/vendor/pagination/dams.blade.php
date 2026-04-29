<nav aria-label="Pagination" class="dams-pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-btn page-prev disabled">‹ Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn page-prev">‹ Prev</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-btn page-dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-btn page-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn page-next">Next ›</a>
    @else
        <span class="page-btn page-next disabled">Next ›</span>
    @endif

    <span class="page-info">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </span>
</nav>
