@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Due')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>Edit Due</h1>

<form method="POST" action="{{ route('dues.update', $due) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="contact_id">Client/Vendor *</label>
        <select id="contact_id" name="contact_id" required>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" {{ old('contact_id', $due->contact_id) == $contact->id ? 'selected' : '' }}>
                    {{ $contact->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="invoice_number">Invoice #</label>
        <input type="text" id="invoice_number" name="invoice_number" maxlength="50" value="{{ old('invoice_number', $due->invoice_number) }}">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" id="description" name="description" maxlength="500" value="{{ old('description', $due->description) }}">
    </div>

    <div class="form-group">
        <label for="total_amount">Total Amount *</label>
        <input type="number" step="0.01" id="total_amount" name="total_amount" value="{{ old('total_amount', $due->total_amount) }}" required>
    </div>

    <div class="form-group">
        <label for="paid_amount">Paid Amount</label>
        <input type="number" step="0.01" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $due->paid_amount) }}">
    </div>

    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach(['BDT', 'USD', 'EUR'] as $cur)
                <option value="{{ $cur }}" {{ old('currency', $due->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="type">Type *</label>
        <select id="type" name="type" required>
            <option value="receivable" {{ old('type', $due->type) == 'receivable' ? 'selected' : '' }}>Receivable</option>
            <option value="payable" {{ old('type', $due->type) == 'payable' ? 'selected' : '' }}>Payable</option>
        </select>
    </div>

    <div class="form-group">
        <label for="due_date">Due Date *</label>
        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $due->due_date->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="last_payment_date">Last Payment Date</label>
        <input type="date" id="last_payment_date" name="last_payment_date" value="{{ old('last_payment_date', optional($due->last_payment_date)->format('Y-m-d')) }}">
    </div>

    <div class="form-group">
        <label for="last_payment_amount">Last Payment Amount</label>
        <input type="number" step="0.01" id="last_payment_amount" name="last_payment_amount" value="{{ old('last_payment_amount', $due->last_payment_amount) }}">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
            @foreach(['pending', 'partial', 'paid', 'overdue', 'written_off'] as $st)
                <option value="{{ $st }}" {{ old('status', $due->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="priority">Priority</label>
        <select id="priority" name="priority">
            @foreach(['low', 'normal', 'high', 'critical'] as $pr)
                <option value="{{ $pr }}" {{ old('priority', $due->priority) == $pr ? 'selected' : '' }}>{{ ucfirst($pr) }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="2">{{ old('notes', $due->notes) }}</textarea>
    </div>

    <div class="form-group">
        <label for="follow_up">Follow‑up Notes</label>
        <textarea id="follow_up" name="follow_up" rows="2">{{ old('follow_up', $due->follow_up) }}</textarea>
    </div>

    <button type="submit" class="btn">Update</button>
</form>
@endsection
