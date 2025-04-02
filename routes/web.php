<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'checkUserType:3'])->group(function () {
    Route::get('/business-contracts', [ContractController::class, 'index'])->name('business-contracts');
    Route::get('/contracts/download/{user_id}', [ContractController::class, 'download'])->name('contracts.download');
    Route::post('/contracts/upload', [ContractController::class, 'upload'])->name('contracts.upload');
});

Route::middleware(['auth', 'checkUserType:2'])->group(function () {
    Route::get('/my-contract', [ContractController::class, 'showAdvertiserContract'])->name('contracts.advertiser');
    Route::post('/my-contract/respond', [ContractController::class, 'respondToContract'])->name('contracts.respond');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
