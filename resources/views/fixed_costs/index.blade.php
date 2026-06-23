@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Fixed Costs')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .badge { padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600; }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-old { background: #f3f4f6; color: #6b7280; }
    .badge-paused { background: #fef3c7; color: #92400e; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 800px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
</style>

<div class="header">
    <h1>Fixed Costs</h1>
    <a href="{{ route('fixed-costs.create') }}" class="btn-primary">+ New Fixed Cost</a>
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
                <th>Item</th>
                <th>Type</th>
                <th>Frequency</th>
                <th>Amount</th>
                <th>BDT</th>
                <th>Effective</th>
                <th>End Date</th>
                <th>Ask Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fixedCosts as $fc)
                <tr>
                    <td>{{ $fc->item }}</td>
                    <td>{{ $fc->type ?? '-' }}</td>
                    <td>{{ $fc->frequency ?? '-' }}</td>
                    <td>{{ number_format($fc->amount, 2) }} {{ $fc->currency }}</td>
                    <td>{{ $fc->bdt_amount ? number_format($fc->bdt_amount, 2).' ৳' : '-' }}</td>
                    <td>{{ $fc->effective_from->format('d M, Y') }}</td>
                    <td>{{ $fc->effective_to ? $fc->effective_to->format('d M, Y') : '—' }}</td>
                    <td>{{ $fc->ask_day ?? '-' }}</td>
                    <td><span class="badge badge-{{ strtolower($fc->status) }}">{{ $fc->status }}</span></td>
                    <td>@include('components.actions', ['editUrl' => route('fixed-costs.edit', $fc), 'deleteUrl' => route('fixed-costs.destroy', $fc)])</td>
                </tr>
            @empty
                <tr><td colspan="10" style="text-align:center;">No fixed costs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $fixedCosts->links() }}
@endsection
