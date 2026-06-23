@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Fixed Cost')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
    small { color: #666; }
</style>

<h1>Edit Fixed Cost</h1>

<form method="POST" action="{{ route('fixed-costs.update', $fixedCost) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="item">Item *</label>
        <input type="text" id="item" name="item" value="{{ old('item', $fixedCost->item) }}" required>
    </div>

    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="">-- Optional --</option>
            @foreach(['Subscription', 'Bill', 'Marketing', 'Rent', 'Salary', 'Other'] as $typ)
                <option value="{{ $typ }}" {{ old('type', $fixedCost->type) == $typ ? 'selected' : '' }}>{{ $typ }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="frequency">Frequency</label>
        <input type="text" id="frequency" name="frequency" value="{{ old('frequency', $fixedCost->frequency) }}">
    </div>

    <div class="form-group">
        <label for="amount">Amount *</label>
        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $fixedCost->amount) }}" required>
    </div>

    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach(['BDT', 'USD', 'EUR'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $fixedCost->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="bdt_amount">BDT Amount</label>
        <input type="number" step="0.01" id="bdt_amount" name="bdt_amount" value="{{ old('bdt_amount', $fixedCost->bdt_amount) }}">
        <small>Leave blank to auto‑calculate if rate available.</small>
    </div>

    <div class="form-group">
        <label for="effective_from">Effective From *</label>
        <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', $fixedCost->effective_from->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="effective_to">Effective To</label>
        <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to', optional($fixedCost->effective_to)->format('Y-m-d')) }}">
    </div>

    <div class="form-group">
        <label for="ask_day">Ask Day (1‑31)</label>
        <input type="number" id="ask_day" name="ask_day" min="1" max="31" value="{{ old('ask_day', $fixedCost->ask_day) }}">
    </div>

    <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
            @foreach(['Active', 'Old', 'Paused'] as $st)
                <option value="{{ $st }}" {{ old('status', $fixedCost->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes', $fixedCost->notes) }}</textarea>
    </div>

    <button type="submit" class="btn">Update</button>
</form>
@endsection
