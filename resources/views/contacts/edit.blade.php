@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Contact')

@section('content')
<div class="form-card">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Edit Contact</h1>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('contacts.update', $contact->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Row 1: Name + Type -->
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $contact->name) }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    @foreach(['client', 'customer', 'vendor', 'employee', 'other'] as $t)
                        <option value="{{ $t }}" {{ old('type', $contact->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Company + Email -->
        <div class="form-row">
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" value="{{ old('company', $contact->company) }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}">
            </div>
        </div>

        <!-- Row 3: Phone + Image -->
        <div class="form-row">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}">
            </div>

            <div class="form-group">
                <label for="image">Change Image</label>
                @if ($contact->image)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ Storage::url($contact->image) }}" alt="Contact image" style="max-width: 120px; max-height: 80px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                @else
                    <p style="color: #6b7280;">No image yet.</p>
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color:#666;">Leave empty to keep the current image. Max 2MB.</small>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <button type="submit" class="btn-primary">Create Contact</button>
            <a href="{{ route('contacts.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
