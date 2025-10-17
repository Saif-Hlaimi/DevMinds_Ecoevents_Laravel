@extends('layouts.admin')
@section('title','Events')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Events</h3>
       
    </div>

   {{-- Search Bar --}}
<form method="GET" action="{{ url('dashboard/admin/events') }}" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    {{-- Success & Error messages --}}
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

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Organizer</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Is Paid</th>
                        <th>Price $</th>

                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($events as $e)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('dashboard.admin.events.update', $e) }}" class="row g-2 align-items-center">
                                @csrf @method('PUT')
                                <input name="title" value="{{ $e->title }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td>
                            <input name="description" value="{{ $e->description }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td>
                            <input name="date" type="datetime-local" value="{{ $e->date ? $e->date->format('Y-m-d\TH:i') : '' }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td>
                            <input name="location" value="{{ $e->location }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td>{{ $e->user->name ?? 'N/A' }}</td>
                        <td>
                            <select name="type" class="form-control form-control-sm mb-1">
                                <option value="onsite" {{ $e->type=='onsite'?'selected':'' }}>Onsite</option>
                                <option value="online" {{ $e->type=='online'?'selected':'' }}>Online</option>
                            </select>
                        </td>
                        <td>
                            <input name="category" value="{{ $e->category }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td>
                            <select name="is_paid" class="form-control form-control-sm mb-1">
                                <option value="0" {{ $e->is_paid==0?'selected':'' }}>Free</option>
                                <option value="1" {{ $e->is_paid==1?'selected':'' }}>Paid</option>
                            </select>
                        </td>
                         <td>
                            <input name="price" value="{{ $e->price }}" class="form-control form-control-sm mb-1">
                        </td>
                        <td class="text-end">
                            <button class="btn btn-success btn-sm mb-1">Save</button>
                            </form>

                            <form method="POST" action="{{ route('dashboard.admin.events.destroy', $e) }}" class="d-inline" onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm mb-1">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
