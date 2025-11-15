@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h2 class="fw-bold text-white mb-4">Edit User - {{ $user->name }}</h2>

    {{-- Form Ubah Role --}}
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mb-4">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-semibold text-white">Select Role</label>
            <select name="role_id" class="form-select w-auto">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected':'' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    <hr class="my-4">

    {{-- Suspend / Activate User --}}
    <div class="card shadow-sm p-3 rounded-4">
        <h5 class="fw-semibold mb-3">Account Status</h5>
        <p>
            Current Status:
            @if($user->is_suspended)
                <span class="badge bg-danger">Suspended</span>
            @else
                <span class="badge bg-success">Active</span>
            @endif
        </p>

        @if(!$user->is_suspended)
            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST"
                  onsubmit="return confirm('Suspend this user?')">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-ban me-1"></i> Suspend User
                </button>
            </form>
        @else
            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST"
                  onsubmit="return confirm('Reactivate this user?')">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-undo me-1"></i> Activate User
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
