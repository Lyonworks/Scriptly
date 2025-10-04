@extends('layouts.admin')
@section('title','Manage Users')
@section('content')
<h2 class="fw-bold mb-4">Users</h2>

<div class="card shadow-sm rounded-4 p-3">
  <div class="table-responsive">
    <table class="table table-theme align-middle mb-0">
        <thead>
            <tr class="text-center">
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="text-center">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name ?? '-' }}</td>
                <td>
                <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
