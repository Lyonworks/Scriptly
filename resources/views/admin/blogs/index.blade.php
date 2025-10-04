@extends('layouts.admin')
@section('title','Manage Blogs')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Manage Blogs</h3>

  <div class="d-flex gap-2">
    <a href="{{ route('blogs.create') }}" class="btn btn-theme">+ Add Blog</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm rounded-4 p-3">
  <div class="table-responsive">
    <table class="table table-theme align-middle mb-0">
      <thead>
        <tr class="text-center">
            <th>Title</th>
            <th>Author</th>
            <th>Content</th>
            <th>Image</th>
            <th>Destinations</th> {{-- kolom baru --}}
            <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($blogs as $blog)
            <tr class="text-center align-middle">
            <td>{{ $blog->title }}</td>
            <td>{{ $blog->author ?? 'N/A' }}</td>
            <td>{{ Str::limit($blog->content, 80) }}</td>
            <td>
                @if($blog->image)
                <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" width="80" class="rounded">
                @else
                <span class="text-muted">No Image</span>
                @endif
            </td>
            <td>
                @if($blog->destinations->count())
                <ul class="list-unstyled mb-0">
                    @foreach($blog->destinations as $d)
                    <li>{{ $d->name }}</li>
                    @endforeach
                </ul>
                @else
                <span class="text-muted">No Destinations</span>
                @endif
            </td>
            <td>
                <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('blogs.destroy',$blog->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this blog?')">Delete</button>
                </form>
            </td>
            </tr>
        @empty
            <tr>
            <td colspan="6" class="text-center text-muted">No blogs found</td>
            </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
