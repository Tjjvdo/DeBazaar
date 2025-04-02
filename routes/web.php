<?php


use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/NewAdvertisement', [AdvertisementController::class, 'newAdvertisement']);

Route::post('/NewAdvertisement', [AdvertisementController::class, 'addAdvertisement']);

Route::get('/Advertisements', [AdvertisementController::class, 'getAdvertisements']);

Route::get('/MyAdvertisements', [AdvertisementController::class, 'getMyAdvertisements']);

Route::get('/Advertisements/{id}/View', [AdvertisementController::class, 'getSingleProduct']);

Route::get('/Advertisements/{id}/Update', [AdvertisementController::class, 'getUpdateSingleProduct']);

Route::post('/Advertisements/{id}/Update', [AdvertisementController::class, 'updateSingleProduct']);
