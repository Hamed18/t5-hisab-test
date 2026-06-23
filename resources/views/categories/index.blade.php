@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Categories')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .btn-edit { background: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .btn-delete { background: #dc2626; color: white; border: none; cursor: pointer; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.85rem; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
</style>

<div class="header">
    <h1>Categories</h1>
    <a href="{{ route('categories.create') }}" class="btn-primary">+ New Category</a>
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
                <th>Name</th>
                <th>Type</th>
                <!--<th>Parent</th>-->
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->type }}</td>
                    <!--<td>{{ $cat->parent->name ?? '-' }}</td>-->
                    <td>{{ $cat->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        @include('components.actions', [
                            'editUrl'   => route('categories.edit', $cat),
                            'deleteUrl' => route('categories.destroy', $cat),
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
