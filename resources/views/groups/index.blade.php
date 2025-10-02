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
      <form method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
          <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Search groups by name or description">
        </div>
        <div class="col-md-3">
          <select name="privacy" class="form-select">
            <option value="">All privacies</option>
            <option value="public" @selected(($privacy ?? '')==='public')>Public</option>
            <option value="private" @selected(($privacy ?? '')==='private')>Private</option>
          </select>
        </div>
        <div class="col-md-3">
          <select name="sort" class="form-select">
            <option value="recent" @selected(($sort ?? '')==='recent')>Newest</option>
            <option value="name" @selected(($sort ?? '')==='name')>Name (A-Z)</option>
            <option value="members_desc" @selected(($sort ?? '')==='members_desc')>Members (High→Low)</option>
            <option value="members_asc" @selected(($sort ?? '')==='members_asc')>Members (Low→High)</option>
            <option value="posts_desc" @selected(($sort ?? '')==='posts_desc')>Posts count</option>
          </select>
        </div>
        <div class="col-md-1 d-grid"><button class="btn btn-outline-primary">Apply</button></div>
      </form>
      <div class="row g-4">
        @forelse ($groups as $group)
          <div class="col-lg-4 col-md-6">
            <div class="team__item">
              <div class="team__item-image" style="border-radius:12px;overflow:hidden;">
                <img src="{{ $group->cover_image ?: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=800&auto=format&fit=crop' }}" alt="group">
              </div>
              <h3 class="mt-2"><a href="{{ route('groups.show',$group->slug) }}">{{ $group->name }}</a></h3>
              <span class="text-muted text-capitalize">{{ $group->privacy }} group • {{ $group->approved_members_count }} members • {{ $group->posts_count ?? 0 }} posts</span>
            </div>
          </div>
        @empty
          <div class="col-12"><p>No groups yet.</p></div>
        @endforelse
      </div>
      <div class="mt-4">{{ $groups->appends(request()->query())->links() }}</div>
    </div>
  </section>
@endsection
