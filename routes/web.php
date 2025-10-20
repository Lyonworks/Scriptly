<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureRole;

// ==================== USER ====================
Route::get('/', [HomeController::class, 'index']);
Route::get('/explore', [ProjectController::class, 'explore'])->name('projects.explore');
Route::get('/editor', [HomeController::class, 'editor'])->name('editor');
Route::get('/projects', [HomeController::class, 'projects']);
Route::get('/projects/{id}', [ProjectController::class, 'show'])->whereNumber('id')->name('projects.show'); // readonly viewer
Route::post('/projects/{id}/like', [ProjectController::class, 'like'])->name('projects.like');
Route::post('/projects/{id}/fork', [ProjectController::class, 'fork'])->name('projects.fork');
Route::post('/projects/{id}/comment', [ProjectController::class, 'comment'])->name('projects.comment');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/projects', [ProjectController::class, 'myProjects'])->name('projects.index');
    Route::post('/projects/save', [ProjectController::class, 'store'])->name('projects.save');
    Route::get('/projects/check-name', [ProjectController::class, 'checkName'])->name('projects.checkName');
    Route::get ('/projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::delete('/projects/delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
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

});
