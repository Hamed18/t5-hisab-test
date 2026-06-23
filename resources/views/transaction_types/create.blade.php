@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Transaction Type')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; }
    input, select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>New Transaction Type</h1>

<form method="POST" action="{{ route('transaction-types.store') }}">
    @csrf
    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
        <small>Unique identifier, e.g., "in", "my-type"</small>
    </div>
    <div class="form-group">
        <label for="label">Label</label>
        <input type="text" id="label" name="label" value="{{ old('label') }}" required>
    </div>
    <div class="form-group">
        <label for="effect">Effect</label>
        <select id="effect" name="effect" required>
            <option value="add" {{ old('effect') == 'add' ? 'selected' : '' }}>Add</option>
            <option value="subtract" {{ old('effect') == 'subtract' ? 'selected' : '' }}>Subtract</option>
        </select>
    </div>
    <!--<div class="form-group">
        <label><input type="checkbox" name="transfer" value="1" {{ old('transfer') ? 'checked' : '' }}> Transfer</label>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
    </div>-->
    <button type="submit" class="btn">Create</button>
</form>
@endsection
