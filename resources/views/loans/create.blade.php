@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Loan')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>New Loan</h1>

<form method="POST" action="{{ route('loans.store') }}">
    @csrf

    <div class="form-group">
        <label for="type">Type *</label>
        <select id="type" name="type" required>
            <option value="borrowed" {{ old('type') == 'borrowed' ? 'selected' : '' }}>Borrowed (আমি নিয়েছি)</option>
            <option value="lent" {{ old('type') == 'lent' ? 'selected' : '' }}>Lent (আমি দিয়েছি)</option>
        </select>
    </div>

    <div class="form-group">
        <label for="person">From/To *</label>
        <input type="text" id="person" name="person" value="{{ old('person') }}" required>
    </div>

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
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
        <label for="bdt_amount">BDT Amount (auto if BDT)</label>
        <input type="number" step="0.01" id="bdt_amount" name="bdt_amount" value="{{ old('bdt_amount') }}">
    </div>

    <div class="form-group">
        <label for="purpose">Purpose</label>
        <input type="text" id="purpose" name="purpose" value="{{ old('purpose') }}">
    </div>

    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}">
    </div>

    <div class="form-group">
        <label for="repaid_amount">Repaid / Received Amount</label>
        <input type="number" step="0.01" id="repaid_amount" name="repaid_amount" value="{{ old('repaid_amount', 0) }}">
    </div>

    <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Repaid" {{ old('status') == 'Repaid' ? 'selected' : '' }}>Repaid</option>
            <option value="Written Off" {{ old('status') == 'Written Off' ? 'selected' : '' }}>Written Off</option>
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn">Create</button>
</form>
@endsection
