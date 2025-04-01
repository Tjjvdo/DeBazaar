<?php


use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/NewAdvertisement', function () {
    return view('newAdvertisement');
});

Route::post('/NewAdvertisement', [AdvertisementController::class, 'addAdvertisement']);

Route::get('/Advertisement', [AdvertisementController::class,'getAdvertisements']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';