@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Business')

@section('content')
<div class="form-card">
    <h1>Edit Business</h1>

    <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $business->name) }}" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $business->slug) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    @foreach(['service', 'product', 'hybrid', 'personal', 'investment'] as $t)
                        <option value="{{ $t }}" {{ old('type', $business->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" value="{{ old('branch', $business->branch) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $business->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $business->phone) }}">
        </div>

        <div class="form-group">
            <label>Current Logo</label>
            @if ($business->logo)
                <div style="margin-bottom:0.5rem;">
                    <img src="{{ Storage::url($business->logo) }}" alt="Logo" style="max-width:120px; max-height:80px; border:1px solid #ddd; border-radius:4px;">
                </div>
            @else
                <p style="color:#6b7280;">No logo</p>
            @endif
        </div>

        <div class="form-group">
            <label for="logo">Change Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*">
            <small>Leave empty to keep the current logo.</small>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $business->is_active) ? 'checked' : '' }}> Active</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $business->is_primary) ? 'checked' : '' }}> Primary Business</label>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="button" class="btn-secondary" style="padding:0.65rem 1.75rem;" onclick="history.back()">Back</button>
            <button type="submit" class="btn-primary" style="padding:0.65rem 1.75rem;">Update</button>
        </div>
    </form>
</div>

<script>
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const excludeId = {{ $business->id }};    // current business ID

    const slugMessage = document.createElement('small');
    slugMessage.style.color = '#dc2626';
    slugInput.parentNode.appendChild(slugMessage);

    const nameMessage = document.createElement('small');
    nameMessage.style.color = '#dc2626';
    nameInput.parentNode.appendChild(nameMessage);

    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    async function updateSlugAndCheckName() {
        const name = nameInput.value.trim();
        const slug = generateSlug(name);
        slugInput.value = slug;

        if (slug.length > 0) {
            try {
                const response = await fetch(
                    `{{ route('businesses.check-slug') }}?slug=${encodeURIComponent(slug)}&exclude_id=${excludeId}`
                );
                const data = await response.json();
                if (data.exists) {
                    slugMessage.textContent = 'This slug is already taken.';
                    slugInput.style.borderColor = '#dc2626';
                } else {
                    slugMessage.textContent = '';
                    slugInput.style.borderColor = '#d1d5db';
                }
            } catch(e) { console.error('Slug check failed', e); }
        } else {
            slugMessage.textContent = '';
            slugInput.style.borderColor = '#d1d5db';
        }

        if (name.length > 0) {
            try {
                const response = await fetch(
                    `{{ route('businesses.check-name') }}?name=${encodeURIComponent(name)}&exclude_id=${excludeId}`
                );
                const data = await response.json();
                if (data.exists) {
                    nameMessage.textContent = 'This name already exists.';
                    nameInput.style.borderColor = '#dc2626';
                } else {
                    nameMessage.textContent = '';
                    nameInput.style.borderColor = '#d1d5db';
                }
            } catch(e) { console.error('Name check failed', e); }
        } else {
            nameMessage.textContent = '';
            nameInput.style.borderColor = '#d1d5db';
        }
    }

    nameInput.addEventListener('input', updateSlugAndCheckName);
</script>

@endsection
