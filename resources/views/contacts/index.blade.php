@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Contacts')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .btn-primary { background: #4f46e5; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; text-decoration: none; font-size: 0.85rem; }
    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 600px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
</style>

<div class="header">
    <h1>Contacts</h1>
    <a href="{{ route('contacts.create') }}" class="btn-primary">+ New Contact</a>
</div>

@include('components.search')

{{-- Modern collapsible filter panel --}}
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
                <th>Name</th>
                <th>Type</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ ucfirst($contact->type) }}</td>
                    <td>{{ $contact->company ?? '-' }}</td>
                    <td>{{ $contact->email ?? '-' }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>
                        @include('components.actions', [
                            'editUrl'   => route('contacts.edit', $contact),
                            'deleteUrl' => route('contacts.destroy', $contact),
                        ])
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">No contacts found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $contacts->links() }}
@endsection
