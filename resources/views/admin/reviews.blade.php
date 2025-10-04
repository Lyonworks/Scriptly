@extends('layouts.admin')
@section('title', 'Manage Reviews')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Manage Reviews</h3>

        <form action="{{ route('reviews.index') }}" method="GET" class="d-flex gap-2 align-items-center">
            <input type="hidden" name="destination_id" value="{{ request('destination_id') }}">

            <div class="dropdown">
                <button class="btn btn-theme dropdown-toggle btn-sm" type="button" id="destinationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $destinations->firstWhere('id', request('destination_id'))?->name ?? 'All Destinations' }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="destinationDropdown">
                    <li>
                        <button class="dropdown-item {{ request('destination_id') ? '' : 'active' }}"
                                type="button"
                                data-value=""
                                data-name="All Destinations"
                                onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=destination_id]').value=v; f.querySelector('#destinationDropdown').innerText = n || 'All Destinations'; f.submit();})(this)">
                            All Destinations
                        </button>
                    </li>
                    @foreach($destinations as $destination)
                        <li>
                            <button class="dropdown-item {{ request('destination_id') == $destination->id ? 'active' : '' }}"
                                    type="button"
                                    data-value="{{ $destination->id }}"
                                    data-name="{{ $destination->name }}"
                                    onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=destination_id]').value=v; f.querySelector('#destinationDropdown').innerText = n || 'All Destinations'; f.submit();})(this)">
                                {{ $destination->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <input type="hidden" name="rating" value="{{ request('rating') }}">
            <div class="dropdown">
                <button class="btn btn-theme dropdown-toggle btn-sm" type="button" id="ratingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ request('rating') ? request('rating').' Stars' : 'All Ratings' }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="ratingDropdown">
                    <li>
                        <button class="dropdown-item {{ request('rating') ? '' : 'active' }}"
                                type="button"
                                data-value=""
                                data-name="All Ratings"
                                onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=rating]').value=v; f.querySelector('#ratingDropdown').innerText = n || 'All Ratings'; f.submit();})(this)">
                            All Ratings
                        </button>
                    </li>
                    @for($i = 5; $i >= 1; $i--)
                        <li>
                            <button class="dropdown-item {{ request('rating') == $i ? 'active' : '' }}"
                                    type="button"
                                    data-value="{{ $i }}"
                                    data-name="{{ $i }} Stars"
                                    onclick="(function(b){var f=b.closest('form'),v=b.getAttribute('data-value'),n=b.getAttribute('data-name'); f.querySelector('input[name=rating]').value=v; f.querySelector('#ratingDropdown').innerText = n || 'All Ratings'; f.submit();})(this)">
                                {{ $i }} Stars
                            </button>
                        </li>
                    @endfor
                </ul>
            </div>

            <a href="{{ route('reviews.index') }}" class="btn btn-theme btn-sm">Reset</a>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded-4 p-3">
      <div class="table-responsive">
        <table class="table table-theme align-middle mb-0">
            <thead>
                <tr class="text-center">
                    <th>User</th>
                    <th>Destination</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr class="text-center">
                    <td>
                        @if($review->user)
                            {{ $review->user->name }}
                        @else
                            {{ $review->guest_name ?? 'Guest' }}
                        @endif
                    </td>
                    <td>{{ $review->destination->name }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ $review->review }}</td>
                    <td>
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
@endsection
