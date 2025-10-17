{{-- admin/donation-causes/donations.blade.php --}}
@extends('layouts.admin')
@section('title', 'Donations for ' . $donationCause->title)
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Donations for {{ $donationCause->title }}</h3>
  <div class="mb-3">
    <a href="{{ route('dashboard.admin.donation-causes.donation-causes') }}" class="btn btn-secondary btn-sm">Back to Causes</a>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Donor</th>
            <th>Amount</th>
            <th>Date</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($donations as $donation)
          <tr>
            <td>{{ $donation->user->name ?? 'Anonymous' }}</td>
            <td>${{ number_format($donation->amount, 2) }}</td>
            <td>{{ $donation->created_at->format('Y-m-d H:i') }}</td>
            <td class="text-end">
              <form method="POST" action="{{ route('admin.donations.destroy', [$donationCause, $donation]) }}" onsubmit="return confirm('Delete this donation?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">No donations found for this cause.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $donations->links() }}</div>
  </div>
</div>
@endsection