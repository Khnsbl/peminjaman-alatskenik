@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul style="display:flex; align-items:center; justify-content:center; gap:6px; list-style:none; padding:0; margin:0;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="display:inline-block; padding:6px 14px; border-radius:8px; font-size:0.82rem; background:rgba(255,255,255,0.03); border:1px solid var(--card-border); color:var(--text-muted); cursor:not-allowed; opacity:0.4;">
                        &laquo; Prev
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-block; padding:6px 14px; border-radius:8px; font-size:0.82rem; background:rgba(255,255,255,0.05); border:1px solid var(--card-border); color:var(--text-secondary); text-decoration:none; transition:all 0.15s;">
                        &laquo; Prev
                    </a>
                </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-block; padding:6px 14px; border-radius:8px; font-size:0.82rem; background:rgba(255,255,255,0.05); border:1px solid var(--card-border); color:var(--text-secondary); text-decoration:none; transition:all 0.15s;">
                        Next &raquo;
                    </a>
                </li>
            @else
                <li>
                    <span style="display:inline-block; padding:6px 14px; border-radius:8px; font-size:0.82rem; background:rgba(255,255,255,0.03); border:1px solid var(--card-border); color:var(--text-muted); cursor:not-allowed; opacity:0.4;">
                        Next &raquo;
                    </span>
                </li>
            @endif
        </ul>

        <div style="text-align:center; margin-top:8px; font-size:0.75rem; color:var(--text-muted);">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            &mdash; Total {{ $paginator->total() }} data
        </div>
    </nav>
@endif