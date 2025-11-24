@extends('layouts.app')
@section('title', 'Online Code Editor')
@section('content')
<section class="hero">
    <div class="hero-content">
        <h1>Write, Preview, Share. Your Code, Anywhere</h1>
        <p>An intuitive online code editor for developers to write, test, and share their creations seamlessly.</p>
        <button class="btn btn-primary">Start Coding Now</button>
    </div>
    <div class="hero-image">
        <img src="{{ asset('storage/assets/hero.png') }}" alt="Code Editor Illustration">
    </div>
</section>

@endsection
