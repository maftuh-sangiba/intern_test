@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <form action="{{ route('app.users.update', $user->id) }}" method="POST" with-submit-crud>
        @csrf
        @method("PUT")

        {{-- @include('admin.users.form') --}}
        @livewire('apps.admin.user-form', ['roles' => $roles, 'user' => $user])

        <button class="btn btn-success btn-sm mt-3">Update User</button>

      </form>

    </div>
  </div>

@endsection
