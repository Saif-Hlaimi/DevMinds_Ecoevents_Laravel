@extends('layouts.app')

@section('title', 'Complaints - '.ucfirst($complaintType->name))

@section('content')
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2>Complaints — {{ ucfirst($complaintType->name) }}</h2>
        <div class="breadcrumb-list">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('complaint-types.index') }}">Types :</a>
            <span class="primary-color">{{ ucfirst($complaintType->name) }}</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h3 class="mb-0">List of Claims ({{ $complaints->total() }})</h3>
            <a href="{{ route('complaints.create', ['type' => $complaintType->id]) }}" class="btn-one">
                <span>New Complaint</span> <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <!-- === Search & Filter Form === -->
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ request('q') }}">
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    @foreach(['closed','pending','resolved','open'] as $status)
                        <option value="{{ $status }}" @selected(request('status')==$status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">-- Priority --</option>
                    @foreach(['low','medium','high'] as $priority)
                        <option value="{{ $priority }}" @selected(request('priority')==$priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="col-md-2">
                <select name="user" class="form-select">
                    <option value="">-- User --</option>
                    @foreach(\App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" @selected(request('user')==$user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-success mt-1">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>

        <!-- === Complaints List === -->
        @if($complaints->count())
            <div class="row gy-4">
                @foreach($complaints as $complaint)
                    <div class="col-lg-4 col-md-6">
                        <div class="donation__item bor p-3">
                            <h3>
                                <a href="{{ route('complaints.show', $complaint) }}">
                                    {{ $complaint->subject }}
                                </a>
                            </h3>

                            <p>{{ Str::limit($complaint->message, 100) }}</p>

                            <p class="mb-1">
                                <strong>Priority :</strong>
                                <span class="badge
                                    @if($complaint->priority == 'high') bg-danger
                                    @elseif($complaint->priority == 'low') bg-success
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($complaint->priority ?? 'Medium') }}
                                </span>
                            </p>

                            <p class="mb-1"><strong>Date :</strong> {{ $complaint->created_at->format('d M Y') }}</p>
                            <p class="mb-1"><strong>User :</strong> {{ $complaint->user->name ?? 'Anonymous' }}</p>

                            <a class="donation__item-arrow" href="{{ route('complaints.show', $complaint) }}">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $complaints->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">No complaints for this type.</p>
            </div>
        @endif
    </div>
</section>
@endsection
