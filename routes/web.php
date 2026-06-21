<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HandoverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// ─── Public routes ────────────────────────────────────────────────────────────

Route::get('/',      [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ─── Protected routes (require staff.auth middleware) ─────────────────────────

Route::middleware('staff.auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activities
    Route::get('/activities',              [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create',       [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities',             [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}/update',  [ActivityController::class, 'update'])->name('activities.update');
    Route::post('/activities/{id}/update', [ActivityController::class, 'saveUpdate'])->name('activities.save');
    Route::delete('/activities/{id}',      [ActivityController::class, 'destroy'])->name('activities.destroy');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update',   [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // Handover
    Route::get('/handover',        [HandoverController::class, 'index'])->name('handover');
    Route::get('/handover/show',   [HandoverController::class, 'index'])->name('handover.show');
    Route::get('/handover/export', [HandoverController::class, 'export'])->name('handover.export');
    Route::post('/handover-notes', [HandoverController::class, 'saveNote'])->name('handover.saveNote');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
