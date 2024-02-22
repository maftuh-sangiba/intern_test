@extends('layouts.admin.app')

@section('content')
  <div class="card">
    <div class="card-body">
      <a href="{{ route('app.transaksi.edit', $transaksi->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>
      <div class="row">
        <div class="col mt-4">
          <h4>Data Produk</h4>
          <div class="card-body table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Qty</th>
                  <th>Harga Produk</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($detail as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->product_price_sell }}</td>   
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection