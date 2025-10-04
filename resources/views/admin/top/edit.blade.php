@extends('layouts.admin')
@section('title','Edit Top Destination')
@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Edit Top Destination</h2>
  <form action="{{ route('top.update',$top->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Destination</label>
      <select name="destination_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($destinations as $dest)
          <option value="{{ $dest->id }}" {{ $top->destination_id == $dest->id ? 'selected':'' }}>
            {{ $dest->name }}
          </option>
        @endforeach
      </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('top.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
