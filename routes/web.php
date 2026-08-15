<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Requires Auth)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('rekap');
    });

    Route::post('/switch-rt', [AuthController::class, 'switchRt']);

    // Protected App Settings Endpoints
    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);
    Route::post('/api/settings/account', [SettingController::class, 'updateAccount']);

    // Protected Warga API Endpoints
    Route::prefix('api')->group(function () {
        Route::get('/warga', [TagihanController::class, 'index']);
        Route::post('/warga/toggle', [TagihanController::class, 'toggle']);
        Route::post('/warga/reset', [TagihanController::class, 'reset']);
        Route::post('/warga/sync', [TagihanController::class, 'sync']);
        Route::post('/warga/store', [TagihanController::class, 'store']);
        Route::put('/warga/{no}', [TagihanController::class, 'update']);
        Route::delete('/warga/{no}', [TagihanController::class, 'destroy']);
    });
});
