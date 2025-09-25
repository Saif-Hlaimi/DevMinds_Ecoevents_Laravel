@extends('layouts.app')
@section('title','Groups')
@section('content')
<section class="page-banner bg-image pt-130 pb-130">
  <div class="container">
    <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Groups</h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}">Home :</a>
      <span class="primary-color">Groups</span>
    </div>
  </div>
  </section>

  <section class="pt-130 pb-130">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Discover groups</h3>
        @auth
          <a class="btn-one" href="{{ route('groups.create') }}"><span>Create group</span> <i class="fa-solid fa-angles-right"></i></a>
        @endauth
      </div>
      <div class="row g-4">
        @forelse ($groups as $group)
          <div class="col-lg-4 col-md-6">
            <div class="team__item">
              <div class="team__item-image" style="border-radius:12px;overflow:hidden;">
                <img src="{{ $group->cover_image ?: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=800&auto=format&fit=crop' }}" alt="group">
              </div>
              <h3 class="mt-2"><a href="{{ route('groups.show',$group->slug) }}">{{ $group->name }}</a></h3>
              <span class="text-muted text-capitalize">{{ $group->privacy }} group • {{ $group->approved_members_count }} members</span>
            </div>
          </div>
        @empty
          <div class="col-12"><p>No groups yet.</p></div>
        @endforelse
      </div>
      <div class="mt-4">{{ $groups->links() }}</div>
    </div>
  </section>
@endsection
