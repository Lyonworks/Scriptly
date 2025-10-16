<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopDestinationController;
use App\Http\Controllers\TrendingTourController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Middleware\EnsureRole;

// ==================== USER ====================
Route::get('/', [HomeController::class, 'index']);
Route::get('/editor', [HomeController::class, 'editor']);
Route::get('/projects', [HomeController::class, 'projects']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('projects', ProjectController::class);
});

// ==================== AUTH =====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== SUPER ADMIN ==============
Route::middleware(['auth', EnsureRole::class . ':1'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->only(['index','edit','update','destroy']);
});

// ==================== ADMIN ====================
Route::middleware(['auth', EnsureRole::class . ':1,2'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Destinations CRUD
    Route::get('/destinations', [DestinationController::class, 'index'])->name('admin.destinations');
    Route::get('/destinations/create', [DestinationController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{id}/edit', [DestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{id}', [DestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

    // Facilities CRUD
    Route::get('/facilities', [FacilityController::class, 'index'])->name('admin.facilities');
    Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('/facilities/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    // Trending CRUD
    Route::resource('/trending', TrendingTourController::class)->names([
        'index'   => 'trending.index',
        'create'  => 'trending.create',
        'store'   => 'trending.store',
        'edit'    => 'trending.edit',
        'update'  => 'trending.update',
        'destroy' => 'trending.destroy',
    ]);

    // Top Destinations CRUD
    Route::resource('/top', TopDestinationController::class)->names([
        'index'   => 'top.index',
        'create'  => 'top.create',
        'store'   => 'top.store',
        'edit'    => 'top.edit',
        'update'  => 'top.update',
        'destroy' => 'top.destroy',
    ]);

    // Reviews (index & destroy only)
    Route::resource('/reviews', ReviewController::class)->only(['index', 'destroy'])->names([
        'index'   => 'reviews.index',
        'destroy' => 'reviews.destroy',
    ]);

    // Blogs CRUD
    Route::resource('/blogs', BlogController::class)->names([
        'index'   => 'admin.blogs',
        'create'  => 'blogs.create',
        'store'   => 'blogs.store',
        'edit'    => 'blogs.edit',
        'update'  => 'blogs.update',
        'destroy' => 'blogs.destroy',
    ]);
});
