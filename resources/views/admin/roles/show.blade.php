@extends('layouts.admin.app')

@section('content')

<div class="card">
  <div class="card-body">
    <table class="table">
      <tr>
        <th>Role</th>
        <td>: {{ $role->role_name }}</td>
      </tr>
    </table>
  </div>
</div>

<ul class="nav nav-pills">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="pill" href="#home">User</a>
  </li>
  @if (check_authorized("004RT"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#menu1">Module</a>
    </li>
  @endif
</ul>

<div class="card">
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane container active" id="home">
        <button class="btn btn-success btn-sm mb-3">Assign user ke role ini</button>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="2" class="text-center">Tidak ada user</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if (check_authorized("004RT"))
        <div class="tab-pane container fade" id="menu1">

          @livewire('apps.admin.role.module-assign', ['role' => $role])

        </div>
      @endif
    </div>
  </div>
</div>

@endsection
