@extends('layouts.admin')
@section('title','Edit Blog')
@section('content')
<div class="container">
  <h2 class="fw-bold mb-4">Edit Blog</h2>
  <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Title --}}
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" id="title"
             class="form-control @error('title') is-invalid @enderror"
             value="{{ old('title', $blog->title) }}" required>
      @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Author --}}
    <div class="mb-3">
      <label for="author" class="form-label">Author</label>
      <input type="text" name="author" id="author"
             class="form-control @error('author') is-invalid @enderror"
             value="{{ old('author', $blog->author) }}">
      @error('author')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Content --}}
    <div class="mb-3">
    <label for="content" class="form-label">Content</label>
    <textarea name="content" id="editor"
                class="form-control @error('content') is-invalid @enderror"
                required>{{ old('content', $blog->content) }}</textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    </div>

    {{-- Current Image --}}
    @if($blog->image)
      <div class="mb-3">
        <label class="form-label d-block">Current Image</label>
        <img src="{{ asset('storage/'.$blog->image) }}" alt="Blog Image"
             class="img-fluid rounded shadow-sm mb-2" style="max-width: 250px;">
      </div>
    @endif

    {{-- Upload New Image --}}
    <div class="mb-3">
      <label for="image" class="form-label">Change Image (optional)</label>
      <input type="file" name="image" id="image"
             class="form-control @error('image') is-invalid @enderror" accept="image/*">
      @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Destinations --}}
    <div class="mb-3">
    <label for="destinations" class="form-label">Suggested Destinations (max 3)</label>
    <select name="destinations[]" id="destinations" class="form-control" multiple>
        @foreach($destinations as $dest)
        <option value="{{ $dest->id }}"
            {{ in_array($dest->id, old('destinations', $selectedDestinations)) ? 'selected' : '' }}>
            {{ $dest->name }} - {{ $dest->location }}
        </option>
        @endforeach
    </select>
    <small class="text-muted">Hold CTRL (Windows) / CMD (Mac) to select up to 3 destinations</small>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
@endsection
