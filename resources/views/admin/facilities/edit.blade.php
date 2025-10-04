@extends('layouts.admin')
@section('title','Edit Facility')

@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Edit Facility</h2>
  <form action="{{ route('facilities.update',$facility->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label class="form-label">Destination</label>
      <select name="destination_id" class="form-select" required>
        @foreach($destinations as $destination)
          <option value="{{ $destination->id }}"
            {{ $facility->destination_id == $destination->id ? 'selected' : '' }}>
            {{ $destination->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Facility</label>
      <input type="text" name="facility" class="form-control" value="{{ $facility->facility }}" required>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('admin.facilities') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
