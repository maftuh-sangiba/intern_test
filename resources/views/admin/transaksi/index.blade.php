@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body table-responsive">

      {{-- @if (check_authorized("003U")) --}}
      <a href="{{ route('app.transaksi.create') }}" class="btn btn-success btn-sm mb-3">Tambah</a>
      {{-- @endif --}}

      {{-- @if (check_authorized("003U")) --}}
        <table class="table table-bordered" id="tableUsers">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Transaksi</th>
              <th>Total</th>
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
      CORE.dataTableServer("tableUsers", "/app/transaksi/get");
    </script>
  @endpush
{{-- @endif --}}
