@extends('layouts.admin.app')

@section('content')
    
  <div class="card">
    <div class="card-body table-responsive">

      <a href="{{ route('app.users.create') }}" class="btn btn-success btn-sm mb-3">Tambah</a>

      <table class="table table-bordered" id="tableUsers">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </div>
  </div>

@endsection

@push('script')
  <script>
    CORE.dataTableServer("tableUsers", "/app/users/get");
  </script>
@endpush