@extends('layouts.admin')
@section('title','Events')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Events</h3>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th>Title</th><th>Date</th><th>Location</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($events as $e)
          <tr>
            <td>
              <form method="POST" action="{{ route('dashboard.admin.events.update', $e) }}" class="row g-2 align-items-center">
                @csrf @method('PUT')
                <div class="col"><input name="title" value="{{ $e->title }}" class="form-control form-control-sm"></div>
            </td>
            <td><input name="date" type="datetime-local" value="{{ $e->date ? $e->date->format('Y-m-d\TH:i') : '' }}" class="form-control form-control-sm"></td>
            <td><input name="location" value="{{ $e->location }}" class="form-control form-control-sm"></td>
            <td class="text-end">
              <button class="btn btn-success btn-sm">Save</button>
              </form>
              <form method="POST" action="{{ route('dashboard.admin.events.destroy', $e) }}" class="d-inline" onsubmit="return confirm('Delete this event?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $events->links() }}</div>
  </div>
</div>
@endsection
