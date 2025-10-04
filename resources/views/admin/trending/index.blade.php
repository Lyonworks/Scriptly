@extends('layouts.admin')
@section('title','Manage Trending Tours')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Manage Trending Tours</h3>

  <div class="d-flex gap-2">
    <a href="{{ route('trending.create') }}" class="btn btn-theme">+ Add Trending</a>
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
          <th>Destination</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tours as $tour)
          <tr class="text-center">
            <td>{{ $tour->destination->name ?? '-' }}</td>
            <td>
              <a href="{{ route('trending.edit',$tour->id) }}" class="btn btn-sm btn-primary">Edit</a>
              <form action="{{ route('trending.destroy',$tour->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
