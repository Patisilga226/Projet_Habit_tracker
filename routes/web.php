<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitLogController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SleepController;
use App\Http\Controllers\HydrationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Habits routes
    Route::resource('habits', HabitController::class);
    
    // Habit Logs routes
    Route::get('/habits/{habit}/logs/create', [HabitLogController::class, 'create'])->name('habit_logs.create');
    Route::post('/habits/{habit}/logs', [HabitLogController::class, 'store'])->name('habit_logs.store');
    Route::get('/habits/{habit}/logs/{log}/edit', [HabitLogController::class, 'edit'])->name('habit_logs.edit');
    Route::put('/habits/{habit}/logs/{log}', [HabitLogController::class, 'update'])->name('habit_logs.update');
    Route::delete('/habits/{habit}/logs/{log}', [HabitLogController::class, 'destroy'])->name('habit_logs.destroy');
    Route::post('/habits/{habit}/quick-log', [HabitLogController::class, 'quickLog'])->name('habit_logs.quick_log');
    
    // Stats routes
    Route::get('/stats', [StatController::class, 'index'])->name('stats.index');
    Route::get('/stats/{habit}', [StatController::class, 'show'])->name('stats.show');
    
    // Sleep tracking routes
    Route::get('/sleep', [SleepController::class, 'index'])->name('sleep.index');
    Route::get('/sleep/create', [SleepController::class, 'create'])->name('sleep.create');
    Route::post('/sleep', [SleepController::class, 'store'])->name('sleep.store');
    Route::get('/sleep/stats', [SleepController::class, 'stats'])->name('sleep.stats');
    
    // Hydration tracking routes
    Route::get('/hydration', [HydrationController::class, 'index'])->name('hydration.index');
    Route::get('/hydration/create', [HydrationController::class, 'create'])->name('hydration.create');
    Route::post('/hydration', [HydrationController::class, 'store'])->name('hydration.store');
    Route::post('/hydration/quick-add', [HydrationController::class, 'quickAdd'])->name('hydration.quick_add');
    Route::get('/hydration/stats', [HydrationController::class, 'stats'])->name('hydration.stats');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

require __DIR__.'/auth.php';
