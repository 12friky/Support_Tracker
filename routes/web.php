<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HandoverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// ─── Public routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    if (session('staff_logged_in')) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'staff_id' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('staff_id', $request->staff_id)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        session()->put('staff_logged_in', true);
        session()->put('staff_id',         $user->staff_id);
        session()->put('staff_user_id',    $user->id);
        session()->put('staff_user_name',  $user->name);
        session()->put('staff_user_role',  $user->role);

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'login' => 'Invalid Staff ID or password.',
    ])->withInput(['staff_id' => $request->staff_id]);
})->name('login.submit');

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
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // Handover
    Route::get('/handover',        [HandoverController::class, 'index'])->name('handover');
    Route::get('/handover/show',   [HandoverController::class, 'index'])->name('handover.show');
    Route::get('/handover/export', [HandoverController::class, 'export'])->name('handover.export');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Logout (POST — CSRF protected)
    Route::post('/logout', function () {
        session()->flush();
        return redirect()->route('login');
    })->name('logout');
});
