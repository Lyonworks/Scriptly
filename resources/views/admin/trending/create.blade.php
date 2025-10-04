@extends('layouts.admin')
@section('title','Create Trending Tour')
@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Create Trending Tour</h2>
  <form action="{{ route('trending.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label class="form-label">Destination</label>
      <select name="destination_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($destinations as $dest)
          <option value="{{ $dest->id }}">{{ $dest->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('trending.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
