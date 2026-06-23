@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Business')

@section('content')
<div class="form-card">
    <h1>New Business</h1>

    <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug (auto-generated if blank)</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    @foreach(['service', 'product', 'hybrid', 'personal', 'investment'] as $t)
                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" value="{{ old('branch') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label for="logo">Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*">
            <small style="color:#666;">Max 2MB. Formats: jpeg, png, jpg, gif, svg.</small>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}> Primary Business</label>
        </div>

        <button type="submit" class="btn-primary" style="padding:0.65rem 1.75rem;">Create</button>
    </form>
</div>

<script>
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    // Slug message element
    const slugMessage = document.createElement('small');
    slugMessage.style.color = '#dc2626';
    slugInput.parentNode.appendChild(slugMessage);

    // Name message element
    const nameMessage = document.createElement('small');
    nameMessage.style.color = '#dc2626';
    nameInput.parentNode.appendChild(nameMessage);

    // Helper to generate slug from name
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

        // --- Check slug uniqueness ---
        if (slug.length > 0) {
            try {
                const response = await fetch(`{{ route('businesses.check-slug') }}?slug=${encodeURIComponent(slug)}`);
                const data = await response.json();
                if (data.exists) {
                    slugMessage.textContent = 'This slug is already taken.';
                    slugInput.style.borderColor = '#dc2626';
                } else {
                    slugMessage.textContent = '';
                    slugInput.style.borderColor = '#d1d5db';
                }
            } catch(e) {
                console.error('Slug check failed', e);
            }
        } else {
            slugMessage.textContent = '';
            slugInput.style.borderColor = '#d1d5db';
        }

        // --- Check name uniqueness ---
        if (name.length > 0) {
            try {
                const response = await fetch(`{{ route('businesses.check-name') }}?name=${encodeURIComponent(name)}`);
                const data = await response.json();
                if (data.exists) {
                    nameMessage.textContent = 'This name already exists.';
                    nameInput.style.borderColor = '#dc2626';
                } else {
                    nameMessage.textContent = '';
                    nameInput.style.borderColor = '#d1d5db';
                }
            } catch(e) {
                console.error('Name check failed', e);
            }
        } else {
            nameMessage.textContent = '';
            nameInput.style.borderColor = '#d1d5db';
        }
    }

    nameInput.addEventListener('input', updateSlugAndCheckName);
</script>

@endsection
