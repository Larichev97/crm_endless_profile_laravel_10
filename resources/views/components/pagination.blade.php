@if ($paginator->hasPages())
    <div class="mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link" aria-disabled="true">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @endif

                @php
                    $start = max($paginator->currentPage() - 4, 1);
                    $end = min($start + 8, $paginator->lastPage());
                @endphp

                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                @endif

                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ ($i == $paginator->currentPage()) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->appends(request()->query())->url($i) }}" @if($i == $paginator->currentPage()) style="background-color: #344767 !important; border-color: #344767 !important;" @endif>{{ $i }}</a>
                    </li>
                @endfor

                @if ($end < $paginator->lastPage() - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif

                @if ($end < $paginator->lastPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->appends(request()->query())->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" aria-disabled="true">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
