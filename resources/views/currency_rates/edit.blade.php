@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Currency Rate')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>Edit Currency Rate</h1>

<form method="POST" action="{{ route('currency-rates.update', $currencyRate) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach(['USD', 'EUR', 'GBP', 'INR', 'CAD', 'AUD', 'SGD', 'MYR'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $currencyRate->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="rate_to_bdt">Rate to BDT *</label>
        <input type="number" step="0.0001" id="rate_to_bdt" name="rate_to_bdt" value="{{ old('rate_to_bdt', $currencyRate->rate_to_bdt) }}" required>
    </div>
    <div class="form-group">
        <label for="effective_from">Effective From *</label>
        <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', $currencyRate->effective_from->format('Y-m-d')) }}" required>
    </div>
    <div class="form-group">
        <label for="effective_to">Effective To</label>
        <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to', $currencyRate->effective_to ? $currencyRate->effective_to->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group">
        <label for="source">Source</label>
        <input type="text" id="source" name="source" value="{{ old('source', $currencyRate->source) }}">
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes', $currencyRate->notes) }}</textarea>
    </div>
    <button type="submit" class="btn">Update</button>
</form>
@endsection
