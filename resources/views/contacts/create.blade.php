@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Contact')

@section('content')
<div class="form-card">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.5rem;">New Contact</h1>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('contacts.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Row 1: Name + Type -->
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    @foreach(['client', 'customer', 'vendor', 'employee', 'other'] as $t)
                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Company + Email -->
        <div class="form-row">
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" value="{{ old('company') }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
        </div>

        <!-- Row 3: Phone + Image -->
        <div class="form-row">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color:#666;">Max 2MB. Formats: jpeg, png, jpg, gif, svg.</small>
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
