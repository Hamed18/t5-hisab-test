@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Businesses')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.35rem 0.85rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.9rem; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 700px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
    .badge-active { background: #d1fae5; color: #065f46; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.8rem; }
    .badge-inactive { background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.8rem; }
</style>

<div class="header">
    <h1>Businesses</h1>
    <a href="{{ route('businesses.create') }}" class="btn-primary">+ New Business</a>
</div>

@include('components.search')
@include('components.filters', ['filters' => $filters ?? []])

@if (session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:0.75rem; border-radius:0.375rem; margin-bottom:1rem;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background:#fee2e2; color:#991b1b; padding:0.75rem; border-radius:0.375rem; margin-bottom:1rem;">
        @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
    </div>
@endif

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Type</th>
                <th>Branch</th>
                <th>Phone</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($businesses as $biz)
                <tr>
                    <td>{{ $biz->name }}</td>
                    <td>{{ $biz->slug }}</td>
                    <td>{{ ucfirst($biz->type) }}</td>
                    <td>{{ $biz->branch ?? '-' }}</td>
                    <td>{{ $biz->phone ?? '-' }}</td>
                    <td><span class="badge-{{ $biz->is_active ? 'active' : 'inactive' }}">{{ $biz->is_active ? 'Yes' : 'No' }}</span></td>
                    <td>
                        @include('components.actions', [
                            'editUrl'   => route('businesses.edit', $biz),
                            'deleteUrl' => route('businesses.destroy', $biz),
                        ])
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;">No businesses found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $businesses->links() }}
@endsection
