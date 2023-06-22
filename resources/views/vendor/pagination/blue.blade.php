@if ($paginator->hasPages())
    <div id="paginator" class="wd-100p ht-80 bg-info d-flex align-items-center justify-content-center mg-t-20">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-dark mg-b-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item">
                <span class="page-link disabled" aria-label="First">
                    <i class="fa fa-angle-double-left"></i>
                </span>
                    </li>
                    <li class="page-item">
                <span class="page-link disabled" aria-label="Prev">
                    <i class="fa fa-angle-left"></i>
                </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->toArray()['first_page_url'] }}" aria-label="First">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                <span class="page-link disabled" aria-label="Next">
                    <i class="fa fa-angle-right"></i>
                </span>
                    </li>
                    <li class="page-item">
                <span class="page-link disabled" aria-label="Last">
                    <i class="fa fa-angle-double-right"></i>
                </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
