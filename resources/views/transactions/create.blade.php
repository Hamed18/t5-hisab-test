@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Transaction')

@section('content')
<div class="form-card">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.5rem;">New Transaction</h1>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type *</label>
                @include('components.searchable-creatable-select', [
                    'name'           => 'type',
                    'options'        => isset($transactionTypes) ? $transactionTypes->pluck('label', 'slug')->toArray() : [],
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
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="business_id">Primary Business *</label>
                @include('components.searchable-creatable-select', [
                    'name'           => 'business_id',
                    'options'        => isset($userBusinesses) ? $userBusinesses->pluck('name', 'id')->toArray() : [],
                    'selected'       => old('business_id', Auth::user()->default_business_id ?? ''),
                    'placeholder'    => 'Select business',
                    'creatable'      => true,
                    'storeRoute'     => route('businesses.store'),
                    'creatableLabel' => 'Create business',
                    'extraFields'    => '',
                ])
            </div>
            <div class="form-group"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Category</label>
                @include('components.searchable-creatable-select', [
                    'name'           => 'category_id',
                    'options'        => isset($categories) ? $categories->pluck('name', 'id')->toArray() : [],
                    'selected'       => old('category_id'),
                    'placeholder'    => 'Search or create category',
                    'creatable'      => true,
                    'storeRoute'     => route('categories.store'),
                    'creatableLabel' => 'Create new category',
                    'extraFields'    => '
                        <input type="hidden" name="type" value="both">
                        <input type="hidden" name="is_active" value="1">
                    ',
                ])
            </div>

            <div class="form-group">
                <label for="account_id">Account *</label>
                @include('components.searchable-creatable-select', [
                    'name'           => 'account_id',
                    'options'        => isset($accounts) ? $accounts->pluck('name', 'id')->toArray() : [],
                    'selected'       => old('account_id'),
                    'placeholder'    => 'Choose an account',
                    'creatable'      => true,
                    'storeRoute'     => route('accounts.store'),
                    'creatableLabel' => 'Create account',
                ])
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="currency">Currency *</label>
                @php
                    $currenciesList = (!isset($currencies) || $currencies->isEmpty()) ? collect(['BDT', 'USD', 'EUR']) : $currencies;
                    if (!$currenciesList->contains('BDT')) { $currenciesList->prepend('BDT'); }
                    $selectedCurrency = old('currency', 'BDT');
                @endphp
                @include('components.searchable-creatable-select', [
                    'name'           => 'currency',
                    'options'        => $currenciesList->mapWithKeys(fn($c) => [$c => $c])->toArray(),
                    'selected'       => $selectedCurrency,
                    'placeholder'    => 'Select currency',
                    'creatable'      => false,
                ])
            </div>

            <div class="form-group">
                <label for="amount">Amount *</label>
                <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}" required>
            </div>
        </div>

        <div class="form-group" id="exchange-rate-group" style="display: {{ $selectedCurrency !== 'BDT' ? 'block' : 'none' }};">
            <label for="exchange_rate">Exchange Rate (to BDT)</label>
            <input type="number" step="0.0001" id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate') }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" maxlength="500" value="{{ old('description') }}">
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Create Transaction</button>
            <a href="{{ route('transactions.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@include('components.transaction-form-script')
@endsection