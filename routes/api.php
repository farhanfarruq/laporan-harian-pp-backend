<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BidangController;
use App\Http\Controllers\Api\PengurusController;
use App\Http\Controllers\Api\JobdeskController;
use App\Http\Controllers\Api\LaporanController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- API Resources ---
    Route::get('/bidang', [BidangController::class, 'index']);
    Route::get('/pengurus', [PengurusController::class, 'index']);
    Route::get('/jobdesk', [JobdeskController::class, 'index']);
    Route::apiResource('/laporan', LaporanController::class)->only(['index', 'store', 'show']);

    // --- Admin Only Routes ---
    Route::middleware('can:access-admin-features')->group(function () {
        // Nanti route untuk CRUD master data bisa ditambahkan di sini
        // Contoh: Route::apiResource('/master/pengurus', MasterPengurusController::class);
    });
});