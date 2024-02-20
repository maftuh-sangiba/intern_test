@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body table-responsive">

      {{-- @if (check_authorized("003U")) --}}
      <a href="{{ route('app.produk.create') }}" class="btn btn-success btn-sm mb-3">Tambah</a>
      {{-- @endif --}}

      {{-- @if (check_authorized("003U")) --}}
        <table class="table table-bordered" id="tableUsers">
          <thead>
            <tr>
              <th>No</th>
              <th>Product Name</th>
              <th>Product Description</th>
              <th>Product Price Capital</th>
              <th>Product Price Sell</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      {{-- @endif --}}

    </div>
  </div>

@endsection

{{-- @if (check_authorized("003U")) --}}
  @push('script')
    <script>
      CORE.dataTableServer("tableUsers", "/app/produk/get");
    </script>
  @endpush
{{-- @endif --}}
