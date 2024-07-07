<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:1'])->group(function () {

    Route::get('/admin/user', [AuthController::class, 'check']);

    Route::prefix('user')->group(function () {
        Route::post('/create', [ProfileController::class, 'create']);
        Route::get('/list', [ProfileController::class, 'list']);
    });
});
