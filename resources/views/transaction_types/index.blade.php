@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Transaction Types')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn { padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .btn-primary { background: #4f46e5; color: white; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-delete { background: #dc2626; color: white; border: none; cursor: pointer; }
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
    }
    table { width: 100%; min-width: 650px; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }

    @media (max-width: 767px) {
        .header { flex-direction: column; align-items: flex-start; }
        h1 { font-size: 1.25rem; }
    }
</style>

<div class="header">
    <h1>Transaction Types</h1>
    <a href="{{ route('transaction-types.create') }}" class="btn btn-primary">+ New Type</a>
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

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Slug</th>
                <th>Label</th>
                <th>Effect</th>
                <th>Transfer</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
                <tr>
                    <td>{{ $type->slug }}</td>
                    <td>{{ $type->label }}</td>
                    <td>{{ $type->effect }}</td>
                    <td>{{ $type->transfer ? 'Yes' : 'No' }}</td>
                    <td>{{ $type->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('components.actions', [
                            'editUrl'   => route('transaction-types.edit', $type),
                            'deleteUrl' => route('transaction-types.destroy', $type),
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
