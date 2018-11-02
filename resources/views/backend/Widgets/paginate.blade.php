@if ($paginator->hasPages())
    <div id="wlog-paginate" class="wlog-paginate">
        <div class="wlog-paginate__inner">
            @if (!$paginator->onFirstPage())
                <a rel="prev" class="wlog-page-item wlog-page-button" href="{{ $paginator->previousPageUrl() }}">&larr; Latest</a>
            @endif

            <span class="wlog-page-item wlog-page-number">{{ $paginator->currentPage() }} / {{ $paginator->total() }}</span>
            
            @if ($paginator->hasMorePages())
            <a rel="next" class="wlog-page-item wlog-page-button" href="{{ $paginator->nextPageUrl() }}">Previous &rarr;</a>
            @endif
        </div>
    </div>
@endif