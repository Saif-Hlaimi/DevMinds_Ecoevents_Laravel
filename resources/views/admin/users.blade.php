@extends('layouts.admin')
@section('title','Users')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Users</h3>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Country</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($users as $u)
          <tr>
            <td>
              <form method="POST" action="{{ route('dashboard.admin.users.update', $u) }}" class="row g-2 align-items-center">
                @csrf @method('PUT')
                <div class="col"><input name="name" value="{{ $u->name }}" class="form-control form-control-sm"></div>
            </td>
            <td><input name="email" value="{{ $u->email }}" class="form-control form-control-sm"></td>
            <td><input name="role" value="{{ $u->role }}" class="form-control form-control-sm"></td>
            <td><input name="phone" value="{{ $u->phone }}" class="form-control form-control-sm"></td>
            <td><input name="country" value="{{ $u->country }}" class="form-control form-control-sm"></td>
            <td class="text-end">
              <button class="btn btn-success btn-sm">Save</button>
              </form>
              <form method="POST" action="{{ route('dashboard.admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $users->links() }}</div>
  </div>
</div>
@endsection
