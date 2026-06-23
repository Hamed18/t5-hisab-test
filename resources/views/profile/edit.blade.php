@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Profile')

@section('content')
<style>
    h2 { margin-bottom: 1rem; }
    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        max-width: 600px;
    }
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; }
    input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 0.375rem;
        font-size: 1rem;
    }
    .btn {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        color: white;
        background: #4f46e5;
        margin-right: 0.5rem;
    }
    .btn:hover { opacity: 0.9; }
    .btn-danger { background: #dc2626; }
    .btn-secondary { background: #6b7280; }
    .status { background: #d1fae5; color: #065f46; padding: 0.5rem; border-radius: 0.25rem; margin-bottom: 1rem; }
    hr { margin: 1.5rem 0; border: 0; border-top: 1px solid #eee; }
    .back-link { display: block; margin-bottom: 1rem; }
</style>

<a href="{{ url('/dashboard') }}" class="back-link">&larr; Back to Dashboard</a>

@if (session('status') === 'profile-updated')
    <div class="status">Profile updated successfully.</div>
@elseif (session('status') === 'password-updated')
    <div class="status">Password changed successfully.</div>
@endif

<!-- Update Name & Email -->
<div class="card">
    <h2>Profile Information</h2>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <button type="submit" class="btn">Create</button>
    </form>
</div>

<!-- Update Password -->
<div class="card">
    <h2>Change Password</h2>
    <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input id="current_password" type="password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

<!-- Delete Account -->
<div class="card">
    <h2>Delete Account</h2>
    <p style="margin-bottom: 1rem; color: #555;">Once deleted, all data will be permanently removed.</p>
    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')

        <div class="form-group">
            <label for="delete_password">Password</label>
            <input id="delete_password" type="password" name="password" required placeholder="Enter your password to confirm">
        </div>

        <button type="submit" class="btn btn-danger">Delete Account</button>
    </form>
</div>
@endsection
