@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h2 class="fw-bold mb-4">Change Role - {{ $user->name }}</h2>
    <form action="{{ route('admin.users.update',$user) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Select Role</label>
            <select name="role_id" class="form-select">
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected':'' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
