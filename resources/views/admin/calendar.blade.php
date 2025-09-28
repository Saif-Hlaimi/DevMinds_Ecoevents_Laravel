@extends('layouts.admin')
@section('title', 'Calendar')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Calendar</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Add Event</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.calendar.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Title</label>
              <input name="title" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Date</label>
              <input name="date" type="datetime-local" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Location</label>
              <input name="location" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button class="btn btn-primary">Create</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">Upcoming Events</div>
        <div class="card-body table-responsive">
          <table class="table align-middle">
            <thead><tr><th>Title</th><th>Date</th><th>Location</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
              @forelse($events as $e)
              <tr>
                <td>{{ $e->title }}</td>
                <td>{{ \Carbon\Carbon::parse($e->date)->format('Y-m-d H:i') }}</td>
                <td>{{ $e->location }}</td>
                <td class="text-end">
                  <form method="POST" action="{{ route('dashboard.calendar.destroy', $e) }}" onsubmit="return confirm('Delete this event?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted">No events</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
