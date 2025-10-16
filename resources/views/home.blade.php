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

<section class="features">
    <div class="feature-card">
        <div class="icon-bg">
            <i class="fas fa-edit"></i> </div>
        <h3>Interactive Editor</h3>
    </div>
    <div class="feature-card">
        <div class="icon-bg">
            <i class="fas fa-eye"></i> </div>
        <h3>Live Preview</h3>
    </div>
    <div class="feature-card">
         <div class="icon-bg">
            <i class="fas fa-share-alt"></i> </div>
        <h3>Save & Share Projects</h3>
    </div>
</section>

<section class="featured-scripts">
    <h2>Featured Scripts</h2>
    <div class="scripts-grid">
        <div class="script-card">
            <img src="https://i.imgur.com/mKeUm1Q.png" alt="Featured Script 1">
            <h4>CSS Art: Octpulse</h4>
        </div>
        <div class="script-card">
            <img src="https://i.imgur.com/s6nC3jW.png" alt="Featured Script 2">
            <h4>JS Game: Space Rocks</h4>
        </div>
        <div class="script-card">
            <img src="https://i.imgur.com/uG9G1jB.png" alt="Featured Script 3">
            <h4>Personal Blog Template</h4>
        </div>
    </div>
</section>
@endsection
