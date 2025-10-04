@extends('layouts.admin')
@section('title','Create Facility')

@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Create Facility</h2>
  <form action="{{ route('facilities.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">Destination</label>
      <select name="destination_id" class="form-select" required>
        <option value="">-- Select Destination --</option>
        @foreach($destinations as $destination)
          <option value="{{ $destination->id }}">{{ $destination->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Facility</label>
      <input type="text" name="facility" class="form-control" placeholder="Facility" required>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.facilities') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
