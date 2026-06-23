@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Transactions')

@section('content')
<style>
    body { font-family: system-ui, sans-serif; background: #f9f9f9; margin: auto; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 0.375rem; white-space: nowrap; }
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
    }
    table { width: 100%; min-width: 800px; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 0.5rem; overflow: hidden; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; font-weight: 600; }
    .positive { color: #16a34a; }
    .negative { color: #dc2626; }
    .actions { display: flex; gap: 0.5rem; }
    .btn-sm { padding: 0.25rem 0.75rem; font-size: 0.8rem; border: none; border-radius: 0.25rem; cursor: pointer; }
    .btn-edit { background: #f59e0b; color: white; text-decoration: none; }
    .btn-delete { background: #dc2626; color: white; }
    .pagination { margin-top: 1rem; display: flex; justify-content: center; }

    @media (max-width: 767px) {
        .header { flex-direction: column; align-items: flex-start; }
        h1 { font-size: 1.25rem; }
    }
</style>

<div class="header">
    <h1>Transactions</h1>
    <a href="{{ route('transactions.create') }}" class="btn">+ New Transaction</a>
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

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Account</th>
                <th>Description</th>
                <th>Amount</th>
                <th>BDT</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $txn)
                <tr>
                    <td>{{ $txn->date->format('d M, Y') }}</td>
                    <td>{{ ucfirst(str_replace('-', ' ', $txn->type)) }}</td>
                    <td>{{ $txn->category->name ?? '-' }}</td>
                    <td>{{ $txn->account->name ?? '-' }}</td>
                    <td>{{ $txn->description }}</td>
                    <td class="{{ in_array($txn->type, ['in','in-partial','pi','refund-in','loan-in','tr-in']) ? 'positive' : 'negative' }}">
                        {{ number_format($txn->amount, 2) }} {{ $txn->currency }}
                    </td>
                    <td>{{ $txn->bdt_amount ? number_format($txn->bdt_amount, 2).' ৳' : '-' }}</td>
                    <td>
                        @include('components.actions', [
                            'editUrl'   => route('transactions.edit', $txn),
                            'deleteUrl' => route('transactions.destroy', $txn),
                        ])
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">No transactions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $transactions->links() }}
</div>
@endsection
