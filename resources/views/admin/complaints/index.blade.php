@extends('layouts.admin')

@section('content')
    <h1>Complaint List</h1>

    {{-- Filter Form --}}
    <form method="GET" class="mb-3">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search...">

        <select name="status">
            <option value="">-- Status --</option>
            @foreach(['open', 'pending', 'resolved', 'closed'] as $status)
                <option value="{{ $status }}" 
                    @if(isset($filters['status']) && $filters['status'] === $status) selected @endif>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary btn-sm">Filter</button>
    </form>

    {{-- Complaints Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject</th>
                <th>User</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Type</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->subject ?? '-' }}</td>
                    <td>{{ $complaint->user->name ?? 'Unknown user' }}</td>
                    <td>{{ ucfirst($complaint->status ?? 'undefined') }}</td>
                    <td>{{ ucfirst($complaint->priority ?? 'undefined') }}</td>
                    <td>{{ $complaint->type->name ?? '-' }}</td>
                    <td>{{ $complaint->assignee->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.complaints.show', $complaint) }}">View</a> |
                        <a href="{{ route('admin.complaints.edit', $complaint) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No complaints found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $complaints->links() }}
    </div>
@endsection
