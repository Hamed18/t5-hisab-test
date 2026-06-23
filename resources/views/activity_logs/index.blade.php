@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Activity Log')

@section('content')
<style>
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 800px; }
    th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; font-weight: 600; }
    .old-value, .new-value { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; vertical-align: middle; }
    .old-value { color: #dc2626; }
    .new-value { color: #16a34a; }
</style>

<h1 style="margin-bottom: 1.5rem;">Activity Log</h1>

@include('components.search')
@include('components.filters', ['filters' => $filters ?? []])

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>ID</th>
                <th>Old Values</th>
                <th>New Values</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M, Y H:i:s') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ class_basename($log->model_type) }}</td>
                    <td>{{ $log->model_id }}</td>
                    <td>
                        @if($log->old_values)
                            @foreach($log->old_values as $key => $val)
                                <span class="old-value" title="{{ $key }}: {{ $val }}">{{ $key }}: {{ $val }}</span><br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($log->new_values)
                            @foreach($log->new_values as $key => $val)
                                <span class="new-value" title="{{ $key }}: {{ $val }}">{{ $key }}: {{ $val }}</span><br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->description }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">No activity recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $logs->links() }}
@endsection
