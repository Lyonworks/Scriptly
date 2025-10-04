@extends('layouts.admin')
@section('title','Manage Facilities')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold">Manage Facilities</h3>

  <div class="d-flex gap-2">
    <form action="{{ route('admin.facilities') }}" method="GET" class="d-flex gap-2 align-items-center">
        <input type="hidden" name="destination_id" value="{{ request('destination_id') }}">

        <div class="dropdown">
            <button class="btn btn-theme btn-sm dropdown-toggle" type="button" id="facilityDestinationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $destinations->firstWhere('id', request('destination_id'))?->name ?: 'All Destinations' }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="facilityDestinationDropdown">
                <li>
                    <button
                        class="dropdown-item {{ request('destination_id') ? '' : 'active' }}"
                        type="button"
                        data-value=""
                        data-name="All Destinations"
                        onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=destination_id]').value=v; f.querySelector('#facilityDestinationDropdown').innerText = n || 'All Destinations'; f.submit();})(this)">
                        All Destinations
                    </button>
                </li>
                @foreach($destinations as $destination)
                    <li>
                        <button
                            class="dropdown-item {{ request('destination_id') == $destination->id ? 'active' : '' }}"
                            type="button"
                            data-value="{{ $destination->id }}"
                            data-name="{{ $destination->name }}"
                            onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=destination_id]').value=v; f.querySelector('#facilityDestinationDropdown').innerText = n || 'All Destinations'; f.submit();})(this)">
                            {{ $destination->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('admin.facilities') }}" class="btn btn-theme btn-sm">Reset</a>
    </form>

    <a href="{{ route('facilities.create') }}" class="btn btn-theme">+ Add Facility</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="alert alert-danger">
    <ul class="m-0">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card shadow-sm rounded-4 p-3">
  <div class="table-responsive">
    <table class="table table-theme align-middle mb-0">
        <thead class="table-light">
            <tr class="text-center">
                <th width="20%">Destination</th>
                <th width="20%">Facility</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $f)
            <tr class="text-center">
                <td>{{ $f->destination->name }}</td>
                <td>{{ $f->facility }}</td>
                <td>
                    <a href="{{ route('facilities.edit', $f->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('facilities.destroy', $f->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this facility?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No facilities found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
  </div>
</div>

@endsection
