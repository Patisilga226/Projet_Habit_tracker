<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HabitApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API pour les habitudes
Route::get('/habits', [HabitApiController::class, 'index']);
Route::get('/habits/{habit}', [HabitApiController::class, 'show']);
Route::get('/habits/{habit}/logs', [HabitApiController::class, 'logs']);
Route::get('/habits/{habit}/stats', [HabitApiController::class, 'stats']);