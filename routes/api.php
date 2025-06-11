<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Api\AuthController;
use App\Http\Controllers\Api\LoansController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemsController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'authenticateLogin');
    Route::post('/logout', 'logout')->middleware('auth:api');
    Route::get('/me', 'me')->middleware('auth:api');
});

Route::middleware(['jwt.auth','role:admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('items', ItemsController::class);
    Route::put('/loans/{loan}/approval', [LoansController::class, 'approval']);
    Route::put('/loans/{loan}/approval_returned', [LoansController::class, 'approval_returned']);

    //loans
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoansController::class, 'index']);
        Route::post('/', [LoansController::class, 'store']);
        Route::get('/{id}', [LoansController::class, 'show']);
        Route::put('/{id}', [LoansController::class, 'update']);
        Route::delete('/{id}', [LoansController::class, 'destroy']);
    });
    
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/loan/{loan_id}', [ReturnController::class, 'create']); // get detail loan untuk return
        Route::post('/', [ReturnController::class, 'store']);
    });
});

Route::middleware(['jwt.auth', 'role:peminjam'])->group(function () {
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoansController::class, 'index']);
        Route::get('/{id}', [LoansController::class, 'show']);
    });

    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/loan/{loan_id}', [ReturnController::class, 'create']);
        Route::post('/', [ReturnController::class, 'store']);

    });
});



