@extends('layouts.admin')
@section('title', 'Ecommerce - Orders')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Ecommerce - Orders</h3>

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

  <div class="card mb-3">
    <div class="card-header">Create Order</div>
    <div class="card-body">
      <form method="POST" action="{{ route('dashboard.ecommerce.orders.store') }}" class="row g-2">
        @csrf
        <div class="col-md-4">
          <label class="form-label">Customer Name</label>
          <input name="customer_name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Customer Email</label>
          <input name="customer_email" type="email" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="pending">pending</option>
            <option value="paid">paid</option>
            <option value="shipped">shipped</option>
            <option value="cancelled">cancelled</option>
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100">Create</button>
        </div>
      </form>
    </div>
  </div>

  @foreach($orders as $o)
  <div class="card mb-3">
    <div class="card-header d-flex align-items-center">
      <form method="POST" action="{{ route('dashboard.ecommerce.orders.update', $o) }}" class="row g-2 flex-grow-1">
        @csrf
        @method('PUT')
        <div class="col-md-3"><input name="customer_name" class="form-control form-control-sm" value="{{ $o->customer_name }}"></div>
        <div class="col-md-3"><input name="customer_email" type="email" class="form-control form-control-sm" value="{{ $o->customer_email }}"></div>
        <div class="col-md-2">
          <select name="status" class="form-select form-select-sm">
            @foreach(['pending','paid','shipped','cancelled'] as $s)
              <option value="{{ $s }}" @selected($o->status===$s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-center">Total: <strong class="ms-1">${{ number_format($o->total,2) }}</strong></div>
        <div class="col-md-2"><button class="btn btn-success btn-sm w-100">Save</button></div>
      </form>
      <form method="POST" action="{{ route('dashboard.ecommerce.orders.destroy', $o) }}" onsubmit="return confirm('Delete order?')" class="ms-2">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm">Delete</button>
      </form>
    </div>
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <form method="POST" action="{{ route('dashboard.ecommerce.orders.items.add', $o) }}" class="row g-2">
          @csrf
          <div class="col-md-6">
            <label class="form-label">Product</label>
            <select name="product_id" class="form-select">
              @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (${{ number_format($p->price,2) }})</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Qty</label>
            <input name="quantity" type="number" min="1" value="1" class="form-control">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary w-100">Add Item</button>
          </div>
        </form>
      </div>
      <div class="table-responsive mt-3">
        <table class="table">
          <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
          <tbody>
            @foreach($o->items as $it)
              <tr>
                <td>{{ $it->product->name }}</td>
                <td>${{ number_format($it->price,2) }}</td>
                <td>{{ $it->quantity }}</td>
                <td>${{ number_format($it->price*$it->quantity,2) }}</td>
                <td class="text-end">
                  <form method="POST" action="{{ route('dashboard.ecommerce.orders.items.remove', [$o,$it]) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">Remove</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endforeach

  {{ $orders->links() }}
</div>
@endsection
