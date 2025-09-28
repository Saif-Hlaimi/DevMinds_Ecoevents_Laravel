@extends('layouts.admin')
@section('title', 'CRM Contacts')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">CRM - Contacts</h3>

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
        <div class="card-header">Add Contact</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.crm.contacts.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input name="name" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control">
            </div>
            <div class="mb-2">
              <label class="form-label">Phone</label>
              <input name="phone" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Notes</label>
              <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <button class="btn btn-primary">Create</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">Contacts</div>
        <div class="card-body table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Notes</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($contacts as $c)
              <tr>
                <td>
                  <form class="d-flex gap-2" method="POST" action="{{ route('dashboard.crm.contacts.update', $c) }}">
                    @csrf
                    @method('PUT')
                    <input name="name" class="form-control form-control-sm" value="{{ $c->name }}">
                </td>
                <td><input name="email" type="email" class="form-control form-control-sm" value="{{ $c->email }}"></td>
                <td><input name="phone" class="form-control form-control-sm" value="{{ $c->phone }}"></td>
                <td><input name="notes" class="form-control form-control-sm" value="{{ $c->notes }}"></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-success">Save</button>
                  </form>
                  <form class="d-inline" method="POST" action="{{ route('dashboard.crm.contacts.destroy', $c) }}" onsubmit="return confirm('Delete this contact?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted">No contacts yet</td></tr>
              @endforelse
            </tbody>
          </table>
          {{ $contacts->links() }}
        </div>
      </div>
    </div>
  </div>
  
</div>
@endsection
