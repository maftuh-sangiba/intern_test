@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <form action="{{ route('app.produk.store') }}" method="POST" with-submit-crud>
        @csrf

        @include('admin.produk.form')

        <button class="btn btn-success btn-sm mt-3">Tambah Produk</button>

      </form>

    </div>
  </div>

@endsection
