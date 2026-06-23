@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Loans')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .badge-active { background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; }
    .badge-repaid { background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; }
    .badge-written-off { background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 800px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
</style>

<div class="header">
    <h1>Loans</h1>
    <a href="{{ route('loans.create') }}" class="btn-primary">+ New Loan</a>
</div>

@include('components.search')
@include('components.filters', ['filters' => $filters ?? []])

@if (session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Person</th>
                <th>Date</th>
                <th>Amount</th>
                <th>BDT</th>
                <th>Purpose</th>
                <th>Due Date</th>
                <th>Repaid</th>
                <th>Outstanding</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                @php
                    $outstanding = $loan->amount - $loan->repaid_amount;
                @endphp
                <tr>
                    <td>{{ $loan->type === 'borrowed' ? 'Borrowed' : 'Lent' }}</td>
                    <td>{{ $loan->person }}</td>
                    <td>{{ $loan->date->format('d M, Y') }}</td>
                    <td>{{ number_format($loan->amount, 2) }} {{ $loan->currency }}</td>
                    <td>{{ $loan->bdt_amount ? number_format($loan->bdt_amount, 2).' ৳' : '-' }}</td>
                    <td>{{ $loan->purpose ?? '-' }}</td>
                    <td>{{ $loan->due_date ? $loan->due_date->format('d M, Y') : '-' }}</td>
                    <td>{{ number_format($loan->repaid_amount, 2) }}</td>
                    <td><strong>{{ number_format($outstanding, 2) }}</strong></td>
                    <td><span class="badge-{{ str_replace(' ', '-', strtolower($loan->status)) }}">{{ $loan->status }}</span></td>
                    <td>@include('components.actions', ['editUrl' => route('loans.edit', $loan), 'deleteUrl' => route('loans.destroy', $loan)])</td>
                </tr>
            @empty
                <tr><td colspan="11" style="text-align:center;">No loans found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $loans->links() }}
@endsection
