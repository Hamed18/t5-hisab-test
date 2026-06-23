@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'New Account')

@section('content')
<style>
    .form-group { margin-bottom: 1rem; }
    label { display: block; font-weight: 500; margin-bottom: 0.25rem; }
    input:not([type="checkbox"]), select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.375rem; font-size: 1rem; }
    .checkbox-group { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem; margin-bottom: 1rem; }
    .checkbox-group input { width: auto; cursor: pointer; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>

<h1>New Account</h1>

<form method="POST" action="{{ route('accounts.store') }}">
    @csrf
    <div class="form-group">
        <label for="name">Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="type">Type *</label>
        <select id="type" name="type" required>
            <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank</option>
            <option value="mobile_wallet" {{ old('type') == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
            <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ old('type') == 'card' ? 'selected' : '' }}>Card</option>
            <option value="crypto" {{ old('type') == 'crypto' ? 'selected' : '' }}>Crypto</option>
            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    <div class="form-group">
        <label for="opening_balance">Opening Balance *</label>
        <input type="number" step="0.01" id="opening_balance" name="opening_balance" value="{{ old('opening_balance', '0') }}" required>
    </div>

    <div class="form-group">
        <label for="account_number">Account Number</label>
        <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}">
    </div>

    <div class="form-group">
        <label for="bank_name">Bank Name</label>
        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
    </div>

    <div class="form-group">
        <label for="branch_name">Branch Name</label>
        <input type="text" id="branch_name" name="branch_name" value="{{ old('branch_name') }}">
    </div>

    <div class="checkbox-group">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
        <label for="is_active">Active</label>
    </div>

    <button type="submit" class="btn">Create</button>
</form>
@endsection