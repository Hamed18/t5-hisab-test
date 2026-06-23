@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Edit Transaction')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input, select, textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; font-size: 1rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 1rem; margin-right: 0.5rem; }
    .btn-secondary { background: #6b7280; color: white; text-decoration: none; }
</style>

<h1>Edit Transaction</h1>

@if ($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" id="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group">
        <label for="type">Type *</label>
        @php
            $transactionTypes = \App\Models\TransactionType::active()->orderBy('label')->get();
        @endphp
        @include('components.searchable-creatable-select', [
            'name'           => 'type',
            'options'        => $transactionTypes->pluck('label', 'slug')->toArray(),
            'selected'       => old('type'),
            'placeholder'    => 'Select a type',
            'creatable'      => true,
            'storeRoute'     => route('transaction-types.store'),
            'creatableLabel' => 'Create new type',
            'extraFields' => '
                <div style="display:flex; gap:0.25rem; width:100%;">
                    <span style="font-size:0.85rem; color:#374151; display:flex; align-items:center; gap:0.1rem;">
                        <span>Add</span>
                        <select name="effect" style="padding:0.2rem 0.3rem; border:1px solid #d1d5db; border-radius:0.25rem; width:auto;">
                            <option value="add">Add</option>
                            <option value="subtract">Subtract</option>
                        </select>
                    </span>
                </div>
                <hr style="margin:0.3rem 0; border-color:#e5e7eb;">
            ',
        ])
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="business_id">Primary Business *</label>
            @include('components.searchable-creatable-select', [
                'name'           => 'business_id',
                'options'        => $userBusinesses->pluck('name', 'id')->toArray(),
                'selected'       => old('business_id', Auth::user()->default_business_id),
                'placeholder'    => 'Select business',
                'creatable'      => true,
                'storeRoute'     => route('businesses.store'),
                'creatableLabel' => 'Create business',
                'extraFields'    => '',
            ])
        </div>
        <div class="form-group"></div>
    </div>

    <div class="form-group">
        <label for="category_id">Category *</label>
        <select id="category_id" name="category_id" required>
            <option value="">-- Select --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->type }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="category_type">Category Type</label>
        <select id="category_type" name="category_type">
            <option value="">-- Select Category Type --</option>
            @foreach ($categoryTypes as $value => $label)
                <option value="{{ $value }}" {{ old('category_type', $transaction->category_type) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="account_id">Account *</label>
        <select id="account_id" name="account_id" required>
            <option value="">-- Select --</option>
            @foreach ($accounts as $acc)
                <option value="{{ $acc->id }}" {{ old('account_id', $transaction->account_id) == $acc->id ? 'selected' : '' }}>
                    {{ $acc->name }} ({{ $acc->currency }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="amount">Amount *</label>
        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required>
    </div>

    @php
        $currencies = \App\Models\CurrencyRate::active()->select('currency')->distinct()->pluck('currency');
        if ($currencies->isEmpty()) { $currencies = collect(['BDT', 'USD', 'EUR']); }
        if (!$currencies->contains('BDT')) { $currencies->prepend('BDT'); }

        $selectedCurrency = old('currency', $transaction->currency);
        $activeRates = \App\Models\CurrencyRate::active()->get()->keyBy('currency');
    @endphp

    <div class="form-group">
        <label for="currency">Currency *</label>
        <select id="currency" name="currency" required>
            @foreach($currencies as $cur)
                <option value="{{ $cur }}" {{ old('currency', $transaction->currency ?? 'BDT') == $cur ? 'selected' : '' }}>{{ $cur }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group" id="exchange-rate-group" style="display: {{ old('currency', $transaction->currency) !== 'BDT' ? 'block' : 'none' }};">
        <label for="exchange_rate">Exchange Rate (to BDT)</label>
        <input type="number" step="0.0001" id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate', $transaction->exchange_rate) }}">
        <div style="font-size:0.875rem;color:#666;">Leave blank to auto-fetch</div>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" id="description" name="description" maxlength="500" value="{{ old('description', $transaction->description) }}">
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="3">{{ old('notes', $transaction->notes) }}</textarea>
    </div>

    <div class="form-group">
        <label for="receipt_id">Receipt ID</label>
        <input type="text" id="receipt_id" name="receipt_id" maxlength="100" value="{{ old('receipt_id', $transaction->receipt_id) }}">
    </div>

    <div class="form-group">
        <label>Current Receipt</label>
        @if ($transaction->receipt_path)
            @php
                $url = Storage::url($transaction->receipt_path);
                $extension = pathinfo($transaction->receipt_path, PATHINFO_EXTENSION);
            @endphp
            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <div style="margin-bottom:0.5rem;">
                    <img src="{{ $url }}" alt="Receipt preview" style="max-width:200px; max-height:150px; border:1px solid #ddd; border-radius:4px;">
                </div>
            @elseif ($extension === 'pdf')
                <div style="margin-bottom:0.5rem;">
                    <a href="{{ $url }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.5rem; background:#f3f4f6; padding:0.5rem 1rem; border-radius:0.375rem; text-decoration:none; color:#1f2937;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-1.5v2H13V7h1.5c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
                        View PDF Receipt
                    </a>
                </div>
            @else
                <div style="margin-bottom:0.5rem;">
                    <a href="{{ $url }}" target="_blank">Download Receipt</a>
                </div>
            @endif
        @else
            <p style="color:#6b7280;">No receipt uploaded yet.</p>
        @endif
    </div>

    <div class="form-group">
        <label for="receipt_file">Replace Receipt File</label>
        <input type="file" id="receipt_file" name="receipt_file" accept=".jpg,.jpeg,.png,.pdf">
        <small style="color:#666;">Leave empty to keep the current receipt. Max 2MB.</small>
    </div>

    <div style="display:flex; gap:1rem;">
        <button type="submit" class="btn">Update Transaction</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@include('components.transaction-form-script')

@endsection