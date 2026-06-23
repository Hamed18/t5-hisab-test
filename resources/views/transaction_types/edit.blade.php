@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Transaction Type')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; }
    input, select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>Edit Transaction Type</h1>

<form method="POST" action="{{ route('transaction-types.update', $transactionType) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $transactionType->slug) }}" required>
    </div>
    <div class="form-group">
        <label for="label">Label</label>
        <input type="text" id="label" name="label" value="{{ old('label', $transactionType->label) }}" required>
    </div>
    <div class="form-group">
        <label for="effect">Effect</label>
        <select id="effect" name="effect" required>
            <option value="add" {{ old('effect', $transactionType->effect) == 'add' ? 'selected' : '' }}>Add</option>
            <option value="subtract" {{ old('effect', $transactionType->effect) == 'subtract' ? 'selected' : '' }}>Subtract</option>
        </select>
    </div>
    <!--<div class="form-group">
        <label><input type="checkbox" name="transfer" value="1" {{ old('transfer', $transactionType->transfer) ? 'checked' : '' }}> Transfer</label>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $transactionType->is_active) ? 'checked' : '' }}> Active</label>
    </div>-->
    <button type="submit" class="btn">Update</button>
</form>
@endsection
