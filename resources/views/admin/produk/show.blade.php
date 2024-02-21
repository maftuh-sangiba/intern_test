@extends('layouts.admin.app')

@section('content')
  
  <div class="card">
    <div class="card-body">

      <a href="{{ route('app.produk.edit', $produk->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>

      <div class="row">
        <div class="col-md-6">
          <table class="table">
            <tr>
              <th>Product Name</th>
              <td>: {{ $produk->product_name }}</td>
            </tr>
            <tr>
              <th>Product Description</th>
              <td>: {{ $produk->product_description }}</td>
            </tr>
            <tr>
              <th>Product Price Capital</th>
              <td>: {{ $produk->product_price_capital }}</td>
            </tr>
            <tr>
              <th>Product Price Sell</th>
              <td>: {{ $produk->product_price_sell }}</td>
            </tr>
          </table>
        </div>
      </div>

    </div>
  </div>

@endsection