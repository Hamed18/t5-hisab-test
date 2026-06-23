@props(['editUrl' => null, 'deleteUrl' => null])

<div style="display: flex; gap: 0.5rem; align-items: center;">

    {{-- Edit Button --}}
    @if ($editUrl)
        <a href="{{ $editUrl }}"
           style="display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;
                  background: #f59e0b; color: white; padding: 0.35rem 0.6rem; border: none;
                  border-radius: 0.375rem; text-decoration: none; font-size: 0.85rem;
                  cursor: pointer; line-height: 1; transition: background 0.2s;"
           onmouseover="this.style.background='#d97706'"
           onmouseout="this.style.background='#f59e0b'"
           title="Edit">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="flex-shrink: 0;">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
            <span class="action-label" style="font-size: 0.85rem;">Edit</span>
        </a>
    @endif

    {{-- Delete Button --}}
    @if ($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST"
              onsubmit="return confirm('Delete this item?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;
                           background: #dc2626; color: white; padding: 0.35rem 0.6rem; border: none;
                           border-radius: 0.375rem; font-size: 0.85rem; cursor: pointer;
                           line-height: 1; transition: background 0.2s;"
                    onmouseover="this.style.background='#b91c1c'"
                    onmouseout="this.style.background='#dc2626'"
                    title="Delete">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="flex-shrink: 0;">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                <span class="action-label" style="font-size: 0.85rem;">Delete</span>
            </button>
        </form>
    @endif

</div>

{{-- Mobile: hide text labels, show only icons --}}
<style>
    @media (max-width: 767px) {
        .action-label {
            display: none;
        }
    }
</style>
