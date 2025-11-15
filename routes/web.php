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

Route::get('/projects/check-name', [ProjectController::class, 'checkName'])->name('projects.checkName');
Route::post('/projects/{slug}/like', [ProjectController::class, 'like'])->name('projects.like');
Route::post('/projects/{slug}/fork', [ProjectController::class, 'fork'])->name('projects.fork');
Route::post('/projects/{slug}/comment', [ProjectController::class, 'comment'])->name('projects.comment');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/projects', [ProjectController::class, 'myProjects'])->name('projects.index');
    Route::post('/projects/save', [ProjectController::class, 'store'])->name('projects.save');
    Route::get('/projects/{project:slug}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project:slug}/update', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project:slug}/delete', [ProjectController::class, 'delete'])->name('projects.delete');
});

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->where('slug', '[A-Za-z0-9\-]+')->name('projects.show');
// ==================== AUTH =====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== SUPER ADMIN ==============
Route::middleware(['auth', EnsureRole::class . ':1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy'])->names([
            'index' => 'users.index',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
        Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    });

// ==================== ADMIN ====================
Route::middleware(['auth', EnsureRole::class . ':1,2'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Project Management
        Route::get('/projects', [AdminController::class, 'projectsIndex'])->name('admin.projects.index');
        Route::get('/projects/{project}', [AdminController::class, 'projectsShow'])->name('admin.projects.show');
        Route::delete('/projects/{project}', [AdminController::class, 'projectsDestroy'])->name('admin.projects.destroy');
        Route::post('/projects/{project}/toggle-visibility', [AdminController::class, 'toggleVisibility'])->name('admin.projects.toggle');

        //Analytics & Logs
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

    });
