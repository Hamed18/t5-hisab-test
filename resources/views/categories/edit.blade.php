@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Category')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>Edit Category</h1>

<form method="POST" action="{{ route('categories.update', $category) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
    </div>
    <div class="form-group">
        <label for="type">Type *</label>
        <select id="type" name="type" required>
            <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Income</option>
            <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Expense</option>
            <option value="both" {{ old('type', $category->type) == 'both' ? 'selected' : '' }}>Both</option>
        </select>
    </div>
    <!--<div class="form-group">
        <label for="parent_id">Parent Category</label>
        <select id="parent_id" name="parent_id">
            <option value="">-- None --</option>
            @foreach ($parentCategories as $pCat)
                <option value="{{ $pCat->id }}" {{ old('parent_id', $category->parent_id) == $pCat->id ? 'selected' : '' }}>
                    {{ $pCat->name }} ({{ $pCat->type }})
                </option>
            @endforeach
        </select>
    </div>-->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="2">{{ old('description', $category->description) }}</textarea>
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}> Active</label>
    </div>
    <button type="submit" class="btn">Update</button>
</form>
@endsection
