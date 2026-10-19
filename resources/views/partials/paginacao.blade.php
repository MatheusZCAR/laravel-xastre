@if ($paginator->hasPages())
    <nav class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-3 border-top" aria-label="Paginação">
        <p class="small text-secondary mb-0">
            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }}
        </p>
        <ul class="pagination pagination-sm">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" rel="prev" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
            </li>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}" @if ($page == $paginator->currentPage()) aria-current="page" @endif>
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" rel="next" aria-label="Próxima página"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
@endif
