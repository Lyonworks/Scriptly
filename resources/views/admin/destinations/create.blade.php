@extends('layouts.admin')
@section('title','Create Destination')

@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Create Destination</h2>

  <form action="{{ route('destinations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Destination name" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Destination location" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="4" placeholder="Enter description" required>{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Image</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.destinations') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
