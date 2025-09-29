@extends('layouts.admin')
@section('title', 'Donation Causes')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Donation Causes</h3>
  <div class="mb-3">
    <a href="{{ route('dashboard.admin.donation-causes.create') }}" class="btn btn-success btn-sm">Create New Cause</a>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Raised Amount</th>
            <th>Goal Amount</th>
            <th>SDG</th>
            <th>Date</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($donationCauses as $cause)
          <tr>
            <td>{{ $cause->title }}</td>
            <td>{{ Str::limit($cause->description, 50) }}</td>
            <td>
              @if($cause->image)
                <img src="{{ asset('storage/' . $cause->image) }}" alt="{{ $cause->title }}" style="max-width: 100px;">
              @else
                No Image
              @endif
            </td>
            <td>${{ number_format($cause->raised_amount, 2) }}</td>
            <td>${{ number_format($cause->goal_amount, 2) }}</td>
            <td>{{ $cause->sdg }}</td>
            <td>{{ $cause->created_at->format('Y-m-d H:i') }}</td>
            <td class="text-end">
              <a href="{{ route('dashboard.admin.donation-causes.edit', $cause) }}" class="btn btn-primary btn-sm me-2">Edit</a>
              <form method="POST" action="{{ route('dashboard.admin.donation-causes.destroy', $cause) }}" onsubmit="return confirm('Delete this donation cause?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center">No donation causes found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $donationCauses->links() }}</div>
  </div>
</div>
@endsection