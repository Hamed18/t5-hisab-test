@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Accounts')

@section('content')
<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    h1 {
        font-size: 1.5rem;
    }

    .btn-primary {
        background: #4f46e5;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        border: none;
        cursor: pointer;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.85rem;
    }

    .account-card {
        background: white;
        padding: 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .account-info {}

    .account-name {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .account-balance {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .currency-badge {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .account-actions {
        display: flex;
        gap: 0.5rem;
    }

    .empty {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
</style>

<div class="header">
    <h1>Accounts</h1>
    <a href="{{ route('accounts.create') }}" class="btn-primary">+ New Account</a>
</div>

<div style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap; margin-bottom: 1rem;">
    <div style="flex: 0 1 300px; max-width: 300px;">
        @include('components.search')
    </div>
    @include('components.filters', ['filters' => $filters ?? []])
</div>

@if (session('success'))
<div style="background: #d1fae5; color: #065f46; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
    @foreach ($errors->all() as $error)
    <div>{{ $error }}</div>
    @endforeach
</div>
@endif

@forelse ($accounts as $acc)
<div class="account-card">
    <div class="account-info">
        <div class="account-name">{{ $acc->name }}</div>
        <div class="currency-badge">{{ $acc->currency }} – {{ ucfirst($acc->type) }}</div>
    </div>
    <div class="account-balance">
        {{ number_format($acc->current_balance, 2) }} ৳
    </div>
    <div class="account-actions">
        @include('components.actions', [
        'editUrl' => route('accounts.edit', $acc),
        'deleteUrl' => route('accounts.destroy', $acc),
        ])
    </div>
</div>
@empty
<div class="empty">No accounts found. Create your first one!</div>
@endforelse
@endsection
