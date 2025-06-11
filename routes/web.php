<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

// Default
// Route::get('/', function () {
//     return view('welcome');
// });

// Custom
// Login
Route::controller(ApiAuthController::class)->middleware('guest')->group(function () {
    // Route::get('/', 'index')->name('login');
    Route::post('/auth-api-login', 'authenticateLogin')->name('auth-login');
});
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/auth-login', 'authenticateLogin')->name('auth-login');
});
// Register
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/auth-register', 'authenticateRegister')->name('auth-register');
});
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// new

Route::resource('categories', CategoryController::class);
Route::resource('items', ItemsController::class);
Route::resource('loans', LoansController::class);

Route::put('/loans/approval/{loan}', [LoansController::class, 'approval'])->name('loans.approval');
Route::put('/loans/approval_return/{loan}', [LoansController::class, 'approval_return'])->name('loans.approval_return');
Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
Route::get('/returns/create/{loan}', [ReturnController::class, 'create'])->name('return.create');
Route::get('/returns/create', [ReturnController::class, 'create'])->name('returns.create');
Route::post('/returns/store', [ReturnController::class, 'store'])->name('returns.store');
Route::get('/returns/{loan}/edit', [ReturnController::class, 'edit'])->name('returns.edit');
Route::put('/returns/{loan}', [ReturnController::class, 'update'])->name('returns.update');


//

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
