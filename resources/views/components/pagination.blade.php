@if ($paginator->hasPages())
    <div class="mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @if ($paginator->previousPageUrl())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                            <i class="fa fa-angle-left"></i>
                            <span class="sr-only">Назад</span>
                        </a>
                    </li>
                @endif

                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    <li class="page-item {{ ($page == $paginator->currentPage()) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($paginator->nextPageUrl())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                            <i class="fa fa-angle-right"></i>
                            <span class="sr-only">Вперёд</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
