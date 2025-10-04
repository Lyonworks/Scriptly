<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\TrendingTourController;
use App\Http\Controllers\Api\TopDestinationController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\UserController;

// ==== PUBLIC ENDPOINTS ====
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{id}', [DestinationController::class, 'show']);
Route::get('/destinations/search', [DestinationController::class, 'search']);
Route::get('/blogs', [BlogController::class, 'index']);

// Reviews: allow guest & user
Route::post('/reviews', [ReviewController::class, 'store']);

// ==== AUTH ====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ==== SUPER ADMIN (role:1) ====
    Route::middleware('role:1')->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class)->only(['index','update','destroy']);
    });

    // ==== ADMIN & SUPER ADMIN (role:1,2) ====
    Route::middleware('role:1,2')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => response()->json(['message' => 'Admin Dashboard']));

        // Destinations CRUD
        Route::apiResource('destinations', DestinationController::class);

        // Facilities CRUD
        Route::apiResource('facilities', FacilityController::class);

        // Trending CRUD
        Route::apiResource('trending', TrendingTourController::class);

        // Top Destinations CRUD
        Route::apiResource('top', TopDestinationController::class);

        // Reviews (index & destroy only)
        Route::apiResource('reviews', ReviewController::class)->only(['index','destroy']);

        // Blogs CRUD
        Route::apiResource('blogs', BlogController::class);
    });
});
