@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Fixed Cost')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
    small { color: #666; }
</style>

<h1>New Fixed Cost</h1>

<form method="POST" action="{{ route('fixed-costs.store') }}">
    @csrf
    <div class="form-group">
        <label for="item">Item *</label>
        <input type="text" id="item" name="item" value="{{ old('item') }}" required>
    </div>

    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="">-- Optional --</option>
            <option value="Subscription" {{ old('type') == 'Subscription' ? 'selected' : '' }}>Subscription</option>
            <option value="Bill" {{ old('type') == 'Bill' ? 'selected' : '' }}>Bill</option>
            <option value="Marketing" {{ old('type') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
            <option value="Rent" {{ old('type') == 'Rent' ? 'selected' : '' }}>Rent</option>
            <option value="Salary" {{ old('type') == 'Salary' ? 'selected' : '' }}>Salary</option>
            <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    <div class="form-group">
        <label for="frequency">Frequency</label>
        <input type="text" id="frequency" name="frequency" value="{{ old('frequency') }}" placeholder="e.g., Monthly, Yearly">
    </div>

    <div class="form-group">
        <label for="amount">Amount *</label>
        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}" required>
    </div>

    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach(['BDT', 'USD', 'EUR'] as $cur)
                <option value="{{ $cur }}" {{ old('currency') == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="bdt_amount">BDT Amount (auto if blank & rate exists)</label>
        <input type="number" step="0.01" id="bdt_amount" name="bdt_amount" value="{{ old('bdt_amount') }}">
        <small>Leave blank to auto‑calculate using current rate.</small>
    </div>

    <div class="form-group">
        <label for="effective_from">Effective From *</label>
        <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', date('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="effective_to">Effective To (blank = ongoing)</label>
        <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to') }}">
    </div>

    <div class="form-group">
        <label for="ask_day">Ask Day (1‑31, for reminder)</label>
        <input type="number" id="ask_day" name="ask_day" min="1" max="31" value="{{ old('ask_day') }}">
    </div>

    <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Old" {{ old('status') == 'Old' ? 'selected' : '' }}>Old</option>
            <option value="Paused" {{ old('status') == 'Paused' ? 'selected' : '' }}>Paused</option>
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn">Create</button>
</form>
@endsection
