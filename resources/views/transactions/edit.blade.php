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
        <label for="business_id">Business *</label>
        <select id="business_id" name="business_id" required>
            @if(isset($userBusinesses))
                @foreach ($userBusinesses as $business)
                    <option value="{{ $business->id }}" {{ old('business_id', $transaction->business_id) == $business->id ? 'selected' : '' }}>
                        {{ $business->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" id="date" name="date" value="{{ old('date', $transaction->date ? $transaction->date->format('Y-m-d') : '') }}" required>
    </div>

    <div class="form-group">
        <label for="type">Type *</label>
        @php
            $transactionTypes = \App\Models\TransactionType::active()->orderBy('label')->get();
        @endphp
        @include('components.searchable-creatable-select', [
            'name'           => 'type',
            'options'        => $transactionTypes->pluck('label', 'slug')->toArray(),
            'selected'       => old('type', $transaction->type),
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

    <div class="form-group">
        <label for="category_custom">Category Options *</label>
        <select id="category_custom" name="category_custom" required>
            <option value="">-- Select Option --</option>
            <option value="xyz" {{ old('category_custom', $transaction->category_type) == 'xyz' ? 'selected' : '' }}>xyz</option>
            <option value="abx" {{ old('category_custom', $transaction->category_type) == 'abx' ? 'selected' : '' }}>abx</option>
            <option value="pqr" {{ old('category_custom', $transaction->category_type) == 'pqr' ? 'selected' : '' }}>pqr</option>
        </select>
    </div>

    <div class="form-group">
        <label for="account_id">Account *</label>
        <select id="account_id" name="account_id" required>
            <option value="">-- Select --</option>
            @if(isset($accounts))
                @foreach ($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ old('account_id', $transaction->account_id) == $acc->id ? 'selected' : '' }}>
                        {{ $acc->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="amount">Amount *</label>
        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required>
    </div>

    <div style="display:flex; gap:1rem; margin-top: 1.5rem;">
        <button type="submit" class="btn">Update Transaction</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@include('components.transaction-form-script')
@endsection