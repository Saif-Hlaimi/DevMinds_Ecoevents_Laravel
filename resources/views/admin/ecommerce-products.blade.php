@extends('layouts.admin')
@section('title', 'Ecommerce - Products')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Ecommerce - Products</h3>

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
        <div class="card-header">Add Product</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.ecommerce.products.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input name="name" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Price</label>
              <input name="price" type="number" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Stock</label>
              <input name="stock" type="number" min="0" class="form-control" required>
            </div>
            <button class="btn btn-primary">Create</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">Products</div>
        <div class="card-body table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $p)
              <tr>
                <td>
                  <form class="d-flex gap-2" method="POST" action="{{ route('dashboard.ecommerce.products.update', $p) }}">
                    @csrf
                    @method('PUT')
                    <input name="name" class="form-control form-control-sm" value="{{ $p->name }}">
                </td>
                <td>
                    <input name="price" type="number" step="0.01" class="form-control form-control-sm" value="{{ $p->price }}">
                </td>
                <td>
                    <input name="stock" type="number" class="form-control form-control-sm" value="{{ $p->stock }}">
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-success">Save</button>
                  </form>
                  <form class="d-inline" method="POST" action="{{ route('dashboard.ecommerce.products.destroy', $p) }}" onsubmit="return confirm('Delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted">No products yet</td></tr>
              @endforelse
            </tbody>
          </table>
          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
