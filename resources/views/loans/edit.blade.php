@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Loan')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>Edit Loan</h1>

<form method="POST" action="{{ route('loans.update', $loan) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="type">Type *</label>
        <select id="type" name="type" required>
            <option value="borrowed" {{ old('type', $loan->type) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
            <option value="lent" {{ old('type', $loan->type) == 'lent' ? 'selected' : '' }}>Lent</option>
        </select>
    </div>

    <div class="form-group">
        <label for="person">From/To *</label>
        <input type="text" id="person" name="person" value="{{ old('person', $loan->person) }}" required>
    </div>

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" id="date" name="date" value="{{ old('date', $loan->date->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="amount">Amount *</label>
        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $loan->amount) }}" required>
    </div>

    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach(['BDT', 'USD', 'EUR'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $loan->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="bdt_amount">BDT Amount</label>
        <input type="number" step="0.01" id="bdt_amount" name="bdt_amount" value="{{ old('bdt_amount', $loan->bdt_amount) }}">
    </div>

    <div class="form-group">
        <label for="purpose">Purpose</label>
        <input type="text" id="purpose" name="purpose" value="{{ old('purpose', $loan->purpose) }}">
    </div>

    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($loan->due_date)->format('Y-m-d')) }}">
    </div>

    <div class="form-group">
        <label for="repaid_amount">Repaid / Received Amount</label>
        <input type="number" step="0.01" id="repaid_amount" name="repaid_amount" value="{{ old('repaid_amount', $loan->repaid_amount) }}">
    </div>

    <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
            @foreach(['Active', 'Repaid', 'Written Off'] as $st)
                <option value="{{ $st }}" {{ old('status', $loan->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes', $loan->notes) }}</textarea>
    </div>

    <button type="submit" class="btn">Update</button>
</form>
@endsection
