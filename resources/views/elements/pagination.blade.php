@if ($paginator->hasPages())
<div class="pagination-wrapper">
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="arrow-left page-prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="arrow-left page-prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a>{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="arrow-right page-next">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        @else
            <a class="arrow-right page-next">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</div>
@endif
