@extends('layouts.admin')
@section('title', 'Edit Complaint')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Complaint</h1>

    <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- Select --</option>
                @foreach(['open', 'pending', 'resolved', 'closed'] as $status)
                    <option value="{{ $status }}" @selected($complaint->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
                <option value="">-- Select --</option>
                @foreach(['low', 'medium', 'high'] as $priority)
                    <option value="{{ $priority }}" @selected($complaint->priority === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
            @error('priority') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Assign to</label>
            <select name="assigned_to" class="form-select">
                <option value="">-- None --</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}" @selected($complaint->assigned_to === $id)>{{ $name }}</option>
                @endforeach
            </select>
            @error('assigned_to') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
