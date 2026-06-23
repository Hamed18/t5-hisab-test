@props([
    'name',
    'options'        => [],
    'selected'       => null,
    'placeholder'    => 'Select...',
    'creatable'      => false,
    'createRoute'    => null,
    'storeRoute'     => null,
    'creatableLabel' => null,
    'extraFields'    => '',
])

@php
    $id = $name . '_' . uniqid();
    $creatableLabel = $creatableLabel ?? 'Create new';
    $storeRoute = $storeRoute ?? '';
    $csrf = csrf_token();
@endphp

<div class="searchable-select-wrapper" data-name="{{ $name }}" data-store="{{ $storeRoute }}" data-csrf="{{ $csrf }}">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $selected }}">

    <div class="ss-trigger" onclick="this.parentElement.classList.toggle('open')">
        <span class="ss-selected-text">
            {{ $options[$selected] ?? $placeholder }}
        </span>
        <span class="ss-arrow">▾</span>
    </div>

    <div class="ss-dropdown hidden">
        {{-- Search + Create container (side-by-side) --}}
        <div class="ss-top-row">
            <input type="text" class="ss-search" placeholder="Search..." oninput="filterSearchableSelect(this)">
            @if($creatable && $storeRoute)
                <button type="button" class="ss-create-btn" style="white-space: nowrap; margin-left: 0.25rem;"
                    onclick="event.stopPropagation(); this.parentElement.nextElementSibling.querySelector('.ss-create-form').classList.toggle('hidden')">
                    + {{ $creatableLabel }}
                </button>
            @elseif($creatable && $createRoute)
                <a href="{{ $createRoute }}" class="ss-create-link" style="white-space: nowrap; margin-left: 0.25rem;" target="_blank">
                    + {{ $creatableLabel }}
                </a>
            @endif
        </div>

        {{-- Inline creation form (hidden by default) --}}
        @if($creatable && $storeRoute)
            <div class="ss-create-area">
                <div class="ss-create-form hidden">
                    <input type="text" class="ss-create-input" placeholder="Name...">
                    <button type="button" class="ss-create-save" onclick="createAndSelectOption(this)">Create</button>
                    <button type="button" class="ss-create-cancel" onclick="this.parentElement.classList.add('hidden')">✕</button>
                    {!! $extraFields !!}
                </div>
            </div>
        @endif

        {{-- Options list --}}
        <div class="ss-options">
            @foreach($options as $value => $label)
                <div class="ss-option {{ $selected == $value ? 'selected' : '' }}" data-value="{{ $value }}" onclick="selectSearchableOption(this)">
                    {{ $label }}
                </div>
            @endforeach
        </div>
    </div>
</div>
