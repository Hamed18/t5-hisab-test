<form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: center;">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search..."
        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; flex: 0 1 300px; max-width: 300px;"
    >
    <button type="submit" style="background: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; display: flex;">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg>
        Search
    </button>
    @if (request('search'))
        <a href="{{ url()->current() }}" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; display: flex;">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
            Clear
        </a>
    @endif
</form>
