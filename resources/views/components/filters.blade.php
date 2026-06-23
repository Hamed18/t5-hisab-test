@props(['filters' => []])

@if (!empty($filters))
<div style="margin-bottom: 1rem;">
    {{-- Filter toggle button --}}
    <button
        type="button"
        onclick="this.nextElementSibling.classList.toggle('hidden'); this.classList.toggle('active');"
        style="background: white; border: 1px solid #d1d5db; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;"
    >
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/></svg>
        Filters
        @if (count(array_filter(request()->except(['page', 'search']))) > 0)
            <span style="background: #4f46e5; color: white; border-radius: 50%; width: 1.25rem; height: 1.25rem; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                {{ count(array_filter(request()->except(['page', 'search']))) }}
            </span>
        @endif
    </button>

    {{-- Filter panel --}}
    <div class="hidden" style="margin-top: 0.5rem; background: white; border: 1px solid #e5e7eb; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ url()->current() }}" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            {{-- Preserve search term --}}
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            @foreach ($filters as $filter)
                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <label for="{{ $filter['name'] }}" style="font-size: 0.85rem; font-weight: 500; color: #374151;">{{ $filter['label'] }}</label>

                    @if (($filter['type'] ?? 'select') === 'select')
                        <select name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background: white; min-width: 160px;">
                            <option value="">All</option>
                            @foreach ($filter['options'] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" {{ request($filter['name']) == $optionValue ? 'selected' : '' }}>
                                    {{ $optionLabel }}
                                </option>
                            @endforeach
                        </select>
                    @elseif (($filter['type'] ?? 'select') === 'date')
                        <input type="date" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" value="{{ request($filter['name']) }}" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; max-width: 160px;">
                    @elseif (($filter['type'] ?? 'select') === 'text')
                        <input type="text" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" value="{{ request($filter['name']) }}" placeholder="{{ $filter['placeholder'] ?? '' }}" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; max-width: 180px;">
                    @endif
                </div>
            @endforeach

            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <button type="submit" style="background: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">Apply</button>
                <a href="{{ url()->current() }}{{ request('search') ? '?search='.request('search') : '' }}"
                   style="background: #fff; color: #374151; border: 1px solid #d1d5db; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
                   Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Active filter chips --}}
    @if (count($activeFilters = array_filter(request()->except(['page', 'search']))) > 0)
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
            @foreach ($activeFilters as $name => $value)
                @php
                    $filterConfig = collect($filters)->firstWhere('name', $name);
                    $label = $filterConfig['label'] ?? $name;
                    $displayValue = $filterConfig['options'][$value] ?? $value;
                @endphp
                <div style="background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>{{ $label }}: {{ $displayValue }}</span>
                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except(['page', $name]))) }}" style="color: #3730a3; text-decoration: none; font-weight: bold; line-height: 1;">&times;</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endif
