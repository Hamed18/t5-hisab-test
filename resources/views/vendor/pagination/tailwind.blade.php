@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="margin-top: 2rem;">
        <ul style="display: flex; justify-content: center; align-items: center; list-style: none; gap: 0.25rem; padding: 0; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li style="padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #e5e7eb; color: #9ca3af; cursor: not-allowed; user-select: none;">
                    &laquo; Prev
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" style="display: block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #fff; color: #4f46e5; text-decoration: none; border: 1px solid #d1d5db;">
                        &laquo; Prev
                    </a>
                </li>
            @endif

            {{-- Pagination Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li style="padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #fff; color: #6b7280;">{{ $element }}</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #4f46e5; color: #fff; font-weight: 600;">
                                {{ $page }}
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="display: block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #fff; color: #4f46e5; text-decoration: none; border: 1px solid #d1d5db;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" style="display: block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #fff; color: #4f46e5; text-decoration: none; border: 1px solid #d1d5db;">
                        Next &raquo;
                    </a>
                </li>
            @else
                <li style="padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #e5e7eb; color: #9ca3af; cursor: not-allowed; user-select: none;">
                    Next &raquo;
                </li>
            @endif
        </ul>
    </nav>
@endif
