@extends('layouts.admin')
@section('title','Edit Destination')

@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Edit Destination</h2>

  <form action="{{ route('destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$destination->name) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" value="{{ old('location',$destination->location) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="4" required>{{ old('description',$destination->description) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      @if($destination->image)
        <img src="{{ asset('storage/'.$destination->image) }}" alt="Destination Image" class="img-thumbnail mb-2" width="200">
      @else
        <p>No image uploaded.</p>
      @endif
    </div>

    <div class="mb-3">
      <label class="form-label">Change Image</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.destinations') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
