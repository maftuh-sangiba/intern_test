@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <form action="{{ route('app.produk.update', $produk->id) }}" method="POST" with-submit-crud>
        @csrf
        @method("PUT")

        @include('admin.produk.form')

        <button class="btn btn-success btn-sm mt-3">Update Produk</button>

      </form>

    </div>
  </div>

@endsection
