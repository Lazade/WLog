@if ($paginator->hasPages())
    <div id="wlog-paginate" class="wlog-paginate">
        <div class="wlog-paginate__inner">
            @if (!$paginator->onFirstPage())
                @if ($paginator->currentPage() == 2) 
                <a rel="prev" class="wlog-page-item wlog-page-button" href="{{ url('/') }}">&larr; Latest</a>
                @else
                <a rel="prev" class="wlog-page-item wlog-page-button" href="{{ preg_replace("~(/page/\d+)?\?page=~", '/page/', $paginator->previousPageUrl()) }}">&larr; Latest</a>
                @endif
            @endif

            <span class="wlog-page-item wlog-page-number">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
            
            @if ($paginator->hasMorePages())
            <a rel="next" class="wlog-page-item wlog-page-button" href="{{  preg_replace("~(/page/\d+)?\?page=~", '/page/', $paginator->nextPageUrl()) }}">Previous &rarr;</a>
            @endif
        </div>
    </div>
@endif