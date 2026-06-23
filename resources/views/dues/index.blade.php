@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Dues')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .badge {
        padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;
    }
    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-partial { background: #fef3c7; color: #92400e; }
    .badge-pending { background: #e0e7ff; color: #3730a3; }
    .badge-overdue { background: #fee2e2; color: #991b1b; }
    .badge-written-off { background: #f3f4f6; color: #6b7280; }
    .aging-ok { color: #16a34a; }
    .aging-warn { color: #f59e0b; }
    .aging-danger { color: #dc2626; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 900px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
</style>

<div class="header">
    <h1>Dues</h1>
    <a href="{{ route('dues.create') }}" class="btn-primary">+ New Due</a>
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
                <th>Invoice #</th>
                <th>Client/Vendor</th>
                <th>Type</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Remaining</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dues as $due)
                @php
                    $remaining = $due->remaining;
                    $today = \Carbon\Carbon::today();
                    $agingDays = $today->diffInDays($due->due_date, false); // negative if past due
                @endphp
                <tr>
                    <td>{{ $due->invoice_number ?: '-' }}</td>
                    <td>{{ $due->contact->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($due->type) }}</td>
                    <td>{{ number_format($due->total_amount, 2) }} {{ $due->currency }}</td>
                    <td>{{ number_format($due->paid_amount, 2) }}</td>
                    <td><strong>{{ number_format($remaining, 2) }}</strong></td>
                    <td>
                        {{ $due->due_date->format('d M, Y') }}
                        <div class="{{ $agingDays < 0 ? 'aging-danger' : ($agingDays <= 15 ? 'aging-warn' : 'aging-ok') }}">
                            @if($agingDays > 0)
                                {{ $agingDays }} days left
                            @elseif($agingDays == 0)
                                Today
                            @else
                                {{ abs($agingDays) }}d overdue
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $due->status }}">
                            {{ ucfirst($due->status) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($due->priority) }}</td>
                    <td>
                        @include('components.actions', [
                            'editUrl' => route('dues.edit', $due),
                            'deleteUrl' => route('dues.destroy', $due),
                        ])
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" style="text-align:center;">No dues found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $dues->links() }}
@endsection
