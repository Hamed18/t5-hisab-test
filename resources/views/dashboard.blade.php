@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Dashboard')

@section('content')
<style>
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 2rem;
        width: 100%;
        max-width: 24rem;
        text-align: center;
    }
    h1 { font-size: 1.5rem; margin-bottom: 1rem; }
    .actions { margin-top: 1.5rem; }
    .btn {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid #ccc;
        border-radius: 0.375rem;
        background: #fff;
        color: #333;
        cursor: pointer;
        transition: background 0.2s;
        margin: 0.25rem;
    }
    .btn:hover { background: #eee; }
    .btn-logout {
        background: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }
    .btn-logout:hover { background: #4338ca; }
</style>

<div style="display: flex; align-items: center; justify-content: center; min-height: 70vh;">
    <div class="card">
        <h1>Dashboard</h1>
        <p>You're logged in, {{ Auth::user()->name }}!</p>

        @php
            // 💡 FIXED: Column switched to opening_balance & business_id constraint removed
            $totalBalance = \App\Models\Account::active()
                ->sum('opening_balance');
        @endphp

        <div style="margin-bottom: 1.5rem; background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="font-weight: 500; color: #6b7280; margin-bottom: 0.25rem;">Total Balance</p>
            <p style="font-size: 2rem; font-weight: 700;">{{ number_format($totalBalance, 2) }} ৳</p>
        </div>

        <div class="actions">
            <a href="{{ route('profile.edit') }}" class="btn">Edit Profile</a>
            <a href="{{ route('transactions.index') }}" class="btn">Transactions</a>

            <form method="POST" action="{{ route('logout') }}" style="display:inline-block">
                @csrf
                <button type="submit" class="btn btn-logout">Log out</button>
            </form>
        </div>
    </div>
</div>
@endsection