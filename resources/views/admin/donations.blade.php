@extends('layouts.admin')
@section('title','Donations')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Donations</h3>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th>Donor</th><th>Email</th><th>Amount</th><th>Cause</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($donations as $d)
          <tr>
            <td>{{ $d->user->name ?? 'User #'.$d->user_id }}</td>
            <td>{{ $d->user->email ?? '-' }}</td>
            <td>${{ number_format($d->amount,2) }}</td>
            <td>{{ $d->donationCause->title ?? '-' }}</td>
            <td>{{ $d->created_at->format('Y-m-d H:i') }}</td>
            <td class="text-end">
              <form method="POST" action="{{ route('dashboard.admin.donations.destroy', $d) }}" onsubmit="return confirm('Delete this donation?')" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $donations->links() }}</div>
  </div>
</div>
@endsection
